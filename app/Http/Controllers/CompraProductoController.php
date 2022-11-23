<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Auth\Guard;

class CompraProductoController extends Controller {

    private $mappingHeaderColumns = array(
        'productoNombre' => 'c_producto.nombre',
        'precio' => 'c_producto.precio',
        'tipoPago' => 'tipo_de_pago');
    
    public function index($idProducto) {
        
        $user=Auth::user();
        if($user==null){
            return view('auth.login');
        }
        $producto = \App\Producto::where('id', $idProducto)->firstOrFail();
        $impuesto = ($producto->precio * 0.16);

        $subtotal = ($producto->precio - ($producto->precio * 0.16));
        $subtotalNum = number_format($subtotal, 2);
        $impuestoNum = number_format($impuesto, 2);
        return view('configuracion.producto.medio_de_pago')
                        ->with('item', $producto)
                        ->with('impuesto', $impuestoNum)
                        ->with('subtotal', $subtotalNum);
    }

    public function verPagarConTarjeta(\Illuminate\Http\Request $request) {
        $idProducto = $request->get('producto');
        return view('configuracion.producto.tarjetas')->with('idProducto', $idProducto);
    }

    public function verPagarReferencia(\Illuminate\Http\Request $request){
        $idProducto = $request->get('producto');
        return view('configuracion.producto.referencia')->with('idProducto', $idProducto);
    }
    
    public function procesarPago(\Illuminate\Http\Request $request) {
        //recuerda verificar tambien key publica en tarjeta.blade.php
        $credencial = $this->obtenerCredenciales();
        \Conekta\Conekta::setApiKey($credencial->oxxoKeyPrivado);
        $conektaTokenId = $request->get('conektaTokenId');
        $idProducto = $request->get('idProducto');
        $tipoPago = $request->get('tipo_pago');
        $producto = \App\Producto::where('id', $idProducto)->firstOrFail();
        $nombre = $request->get('nombre');
        $user = Auth::user();
        if($tipoPago!=3){
            $direccion1 = $request->get('direccion1');
            $direccion2 = $request->get('direccion2');
            $ciudad = $request->get('ciudad');
            $estado = $request->get('estado');
            $pais = $request->get('pais');
            $zip = $request->get('zip');
            $direccion2Txt = ($direccion2 != null ? $direccion2 : "");
            $validator = Validator::make($request->all(), [
                        'nombre' => 'required',
                        'direccion1' => 'required',
                        'ciudad' => 'required',
                        'estado' => 'required',
                        'pais' => 'required',
                        'zip' => 'required|numeric'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                        'nombre' => 'required'
            ]);
        }
        if ($validator->fails()) {
            $view="configuracion.producto.tarjetas";
            if($tipoPago==3){
                $view="configuracion.producto.referencia";
            }
            return redirect($view)->withErrors($validator)->withInput();
        }
        try {
            $order = \Conekta\Order::create(
                            array(
                                "line_items" => array(
                                    array("id" => $producto->id,
                                        "name" => $producto->nombre,
                                        "unit_price" => str_replace(".", "", $producto->precio),
                                        "quantity" => 1)
                                ),
                                "currency" => "MXN",
                                "customer_info" => array(
                                    //"customer_id" => $cliente->id
                                    //Su existe cliente es customer_id si no los de abajo
                                    "object" => "customer_info",
                                    "name" => $nombre,
                                    "email" => $user->email,
                                    "phone" => "+5215555555555"
                                )
                                , //customer_info
                                "shipping_contact" => array(
                                    "phone" => "+525555555555",
                                    "receiver" => $nombre,
                                    "address" => array(
                                        "street1" => ($tipoPago!=3?$direccion1:"street"),
                                        "street2" => ($tipoPago!=3?$direccion2Txt:"street"),
                                        "city" => ($tipoPago!=3?$ciudad:"Mexico"),
                                        "state" => ($tipoPago!=3?$estado:"Mexico"),
                                        "country" => ($tipoPago!=3?$pais:"Mexico"),
                                        "postal_code" => ($tipoPago!=3?$zip:"00000"),
                                        "residential" => true
                                    )//address
                                )
                                ,
                                "charges" => array(array("payment_method" => array("token_id" => $conektaTokenId, "type" => ($tipoPago!=3?"card":"oxxo_cash"))))
                            )//order
            );
            $usuarioContribuyente = new \App\UsuarioContribuyente();
            $contEmisorAct = $usuarioContribuyente->getContribuyenteByUsuario($user->id);
            $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($user->id);
            $email=new \App\Servicio\EmailService();
            Log::error('*******$tipoPago*********['.$tipoPago.']');
            if($tipoPago!=3){
                $pContri = new \App\ProductoContribuyente();
                $pContri->guardar($contEmisor->id, $producto->id, $order->id, "Tarjeta", "Pagado");
                //Actualizar timbres
                $usuarioContribuyente->agregarTimbres($contEmisor->id,$producto->timbre);
                //Actualizar en menu
                $contEmisorPadre=$usuarioContribuyente->getContribuyenteByUsuario($contEmisor->id_contribuyente);
                $request->session()->put('timbres',$contEmisorPadre->timbres_restantes);
                $email->sendPagoBienvenida($user->email,null, $producto,true);
                return view('configuracion.producto.comprado')
                                ->with('contEmisor', $contEmisorAct)
                                ->with('mensaje', "Se pago correctamente");
            }else{
                $pdf=$this->getPdfReferencia($producto,$order->charges[0]->payment_method->reference);
                $output = $pdf->output();
                Storage::disk('local')->put($order->charges[0]->payment_method->reference.'_referencia.pdf', $output);
                $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                $pdfStorage=$storagePath.$order->charges[0]->payment_method->reference.'_referencia.pdf';
                $email->sendPagoBienvenida($user->email,$pdfStorage, $producto,false);
                Storage::disk('local')->delete($order->charges[0]->payment_method->reference.'_referencia.pdf');
                (new \App\TmpOxxoReferencia())->guardar($contEmisor->id, 
                        $order->charges[0]->payment_method->reference, $producto->id, $order->id);
                return view('configuracion.producto.comprado')
                                ->with('contEmisor', $contEmisorAct)
                                ->with('mensaje', "Se envia referencia de pago al correo <strong>".$user->email."</strong>");
            }
        } catch (\Conekta\ProccessingError $error) {
            $view="configuracion.producto.tarjetas";
            if($tipoPago==3){
                $view="configuracion.producto.referencia";
            }
            return view($view)
                            ->with('idProducto', $idProducto)
                            ->with('errorConeckt', $error->message);
        } catch (\Conekta\ParameterValidationError $error) {
            $view="configuracion.producto.tarjetas";
            if($tipoPago==3){
                $view="configuracion.producto.referencia";
            }
            return view($view)
                            ->with('idProducto', $idProducto)
                            ->with('errorConeckt', $error->message);
        } catch (\Conekta\Handler $error) {
            //dd($error->message);
            $view="configuracion.producto.tarjetas";
            if($tipoPago==3){
                $view="configuracion.producto.referencia";
            }
            //$error->message
            return view($view)
                            ->with('idProducto', $idProducto)
                            ->with('errorConeckt', $error->message);
        }
    }
    
    public function referenciaPagada(Request $request){
        Log::error('*******referenciaPagada*********');
        Log::error("*******request:[".$request."]*********");
        $orderId=null;$status=null;
        foreach ($request['data']['object'] as $key => $value){ 
            $keys = implode(",", (array) $key);
            if($keys == "order_id"){
                $orderId=$request['data']['object']['order_id'];
                $status=$request['data']['object']['status'];
            }
            //Log::error("*******key:[".$keys."]");
            /*if(is_array($key)==false) {
                Log::error("*******key:[".$key."],value:[".$value."]*********");
            }*/
        }
        if($orderId!=null && $status=="paid"){
            Log::error("*******orderId:[".$orderId."]*********");
            Log::error("*******status:[".$status."]*********");
            $usuarioContribuyente = new \App\UsuarioContribuyente();
            $tmpRef=(new \App\TmpOxxoReferencia())->getByIdOrder($orderId);
            $pContri = new \App\ProductoContribuyente();
            $validPago=$pContri->getProdContribuyenteByOrder($tmpRef->id_order);
            if($validPago==null){
                $producto = \App\Producto::where('id', $tmpRef->id_producto)->firstOrFail();
                $email=new \App\Servicio\EmailService();
                $contEmisor=(new \App\Contribuyente())->getContribuyenteById($tmpRef->id_contribuyente_emisor);
                $pContri->guardarConReferencia($tmpRef->id_contribuyente_emisor, $tmpRef->id_producto,
                                $tmpRef->id_order, "Referencia",$tmpRef->referencia ,"Pagado");
                $usuarioContribuyente->agregarTimbres($tmpRef->id_contribuyente_emisor,$producto->timbre);
                $email->sendPagoBienvenida($contEmisor->correo_electronico,null, $producto,true);
            }
        }
    }
    
    public function getPdfReferencia($producto,$idReferencia){
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $docPdf=new \App\Pojo\DocumentoPDF();
        $data = $docPdf->getDataOxxoReferencia($producto,$idReferencia,$storagePath.'oxxopay_brand.png');
        $view= \View::make('pdf/oxxoReferencia',compact('data'))->render();
        $pdf=\App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf;
    }
    
    public function getListaProductosComprado(){
        $user = Auth::user();
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($user->id);
        $contEmisorPadre = $usuarioContribuyente->getContribuyenteByUsuario($contEmisor->id_users);
        return view('configuracion.producto.comprado')->with('contEmisor', $contEmisorPadre);
    }
    
    public function getListaProductosJson(\Illuminate\Http\Request $request) {
        $page = $request->get('page');
        $limit = $request->get('rows');
        //columna order
        $sidx = $request->get('sidx');
        $sord = $request->get('sord');
        if (!$sidx) {
            $sidx = 1;
        }
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $pContri = new \App\ProductoContribuyente();
        $count = $pContri->getTotalProductoslistaGrid($contEmisor->id);
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = $limit * $page - $limit; // do not put $limit*($page - 1)
        $responce = new \stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sidx = $this->getValueHeader($sidx);
        $lista = $pContri->getProductosListaGrid($sidx, $sord, $start, $limit,$contEmisor->id);
        $i = 0;
        foreach ($lista as $obj) {
            $responce->rows[$i]['id'] = $i;
            $responce->rows[$i]['cell'] = array($obj->nomProducto, 
                $obj->precioProducto,
                $obj->referencia, 
                $obj->tipo_de_pago,
                $obj->estatus,
                $obj->created_at);
            $i++;
        }
        return \Response::json($responce);
    }

    private function getValueHeader($val){
        foreach($this->mappingHeaderColumns as $key => $value)
        {
            if($key==$val){
                return $value; 
            }
        }
        return $val;
    }    
    
    private function obtenerCredenciales() {
        $environment = \App::environment();
        $confGral = new \App\ConfiguracionGral();
        $responce = new \stdClass();
        if ($environment != 'Production') {
            $confU = $confGral->getByNombre("oxxo_key_privado_desa");
            $responce->oxxoKeyPrivado = $confU->valor;
            $confUrl = $confGral->getByNombre("oxxo_key_publico_desa");
            $responce->oxxoKeyPublico = $confUrl->valor;
        } else {
            $confU = $confGral->getByNombre("oxxo_key_privado_prod");
            $responce->oxxoKeyPrivado = $confU->valor;
            $confUrl = $confGral->getByNombre("oxxo_key_publico_prod");
            $responce->oxxoKeyPublico = $confUrl->valor;
        }
        return $responce;
    }

}
