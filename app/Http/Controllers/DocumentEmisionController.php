<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Session;
use \Crypt;
use App\TipoDocumento;
use App\Pais;
use App\Estado;
use App\Unidad;
use App\TipoImpuesto;
use App\Impuesto;
use Validator;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentEmisionController extends Controller {

    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    private function asignarDatosGenerales(\Illuminate\Http\Request $request) {
        $tipoDoc = $request->get('tipoDoc');
        $serie = $request->get('serie');
        $numero = $request->get('numero');
        $fechaEmision = $request->get('fecha_emision');
        $formaPago = $request->get('forma_pago');
        $tipoCambio = $request->get('tipo_cambio');
        $moneda = $request->get('moneda');
        $metodoPago = $request->get('metodo_pago');
        $condicionesPago = $request->get('condiciones_pago');
        $tipoComprobante = $request->get('tipo_comprobante');
        $doc = $request->session()->get('doc');
        if ($doc == null) {
            $doc = new \App\Pojo\DocumentoEmision();
        }
        $doc->paso = 2;
        $doc->idTipoDoc = ($tipoDoc != 0 ? $tipoDoc : $doc->idTipoDoc);
        $doc->serie = ($serie != null ? $serie : $doc->serie);
        $doc->numero = ($numero != null ? $numero : $doc->numero);
        $doc->fechaEmision = ($fechaEmision != null ? $fechaEmision : $doc->fechaEmision);
        $doc->idFormaPago = ($formaPago != null ? $formaPago : $doc->idFormaPago);
        $doc->tipoCambio = ($tipoCambio != null ? $tipoCambio : $doc->tipoCambio);
        $doc->moneda = ($moneda != null ? $moneda : $doc->moneda);
        $doc->metodoPago = ($metodoPago != null ? $metodoPago : $doc->metodoPago);
        $doc->condicionesPago = ($condicionesPago != null ? $condicionesPago : $doc->condicionesPago);
        $doc->tipoComprobante = ($tipoComprobante != null ? $tipoComprobante : $doc->tipoComprobante);
        $docTmp = $this->actualizaEmisionTmp($doc, null);
        $request->session()->put('doc', $docTmp);
    }

    private function asignarTotales(\Illuminate\Http\Request $request,$isPrevio) {
        $descuento = $request->get('descuento_tot');
        $descDescuento = $request->get('desc_descuento_tot');
        $otroImporteRet = $request->get('otro_imp_ret_tot');
        $otroImporteTras = $request->get('otro_imp_tras_tot');
        $importeRet = $request->get('total_imp_ret_tot');
        $importeTras = $request->get('total_imp_tras_tot');
        $subtotal = $request->get('sub_total_tot');
        $total = $request->get('total_tot');
        $conceptos = $request->get('conceptos');
        $pagosList = $request->get('pagosList');
        $impuestos = $request->get('impuestos');
        $pedimentos = $request->get('pedimentos');
        $totalImporteRet = $request->get('total_imp_ret_tot');
        $totalImporteTras = $request->get('total_imp_tras_tot');
        $porcTasaImp = $request->get('porc_tasa_imp');
        $doc = $request->session()->get('doc');
        if ($doc == null) {
            $doc = new \App\Pojo\DocumentoEmision();
        }
        $doc->paso = 0;
        $doc->pedimentos = $pedimentos;
        $doc->impuestos = $impuestos;
        $doc->conceptos = $conceptos;
        $doc->porcTasaImp = $porcTasaImp;
        $doc->total = $total;
        $doc->subtotal = $subtotal;
        $doc->importeTras = $importeTras;
        $doc->importeRet = $importeRet;
        $doc->otroImporteTras = $otroImporteTras;
        $doc->otroImporteRet = $otroImporteRet;
        $doc->descDescuento = $descDescuento;
        $doc->descuento = $descuento;
        $doc->totalImporteRet = $totalImporteRet;
        $doc->totalImporteTras = $totalImporteTras;
        $doc->totalImporte = $totalImporteTras - $totalImporteRet;
        $doc->pagosList=$pagosList;
        if($isPrevio==false){
            $docTmp = $this->actualizaEmisionTmp($doc, null);
            $request->session()->put('doc', $docTmp);
        }
        $request->session()->put('doc', $doc);
    }

    private function actualizaEmisionTmp(\App\Pojo\DocumentoEmision $doc, $idContribuyenteReceptor) {
        $tmp = new \App\EmisionTmp();
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $docJson = json_encode($doc);
        $tmpSaved = null;
        if ($doc->idEmisionTmp == 0) {
            $coutTmp = $tmp->getTotalPorEmisor($contEmisor->id);
            if ($coutTmp > 8) {
                $tmpUlt = $tmp->getUltimoByIdContribuyenteEmisorOrderAsc($contEmisor->id);
                $this->eliminarTmpEmision($tmpUlt,$tmp);
            }
            $tmpSaved = $tmp->agregar($contEmisor->id, $doc->idTipoDoc, $doc->serie, $doc->numero, 0.0, $doc->paso, $idContribuyenteReceptor, $docJson);
        } else {
            $tmp->actualizar($doc->idEmisionTmp, $contEmisor->id, $doc->idTipoDoc, $doc->serie, $doc->numero, $doc->total, $doc->paso, $idContribuyenteReceptor, $docJson);
        }
        if ($tmpSaved != null){
            $doc->idEmisionTmp = $tmpSaved->id;
        }
        return $doc;
    }

    private function eliminarTmpEmision($value,$tmp) {
        if ($value !== null) {
            $tmp->eliminar($value->id);
        }
    }

    public function verPrevio(\Illuminate\Http\Request $request){
            $docSession = $request->session()->get('doc');
            $this->asignarTotales($request,true);
            //dd($request);
            $tmp=sys_get_temp_dir();
            $docService = new \App\Servicio\DocumentService();
            $conceptos = $docService->getListaConcepto($docSession);
            if($docSession->idTipoDoc == 75){
                $listPago = $docService->getListaComplementoPago($docSession);
                $docSession->pagos=$listPago;
            }
            $docSession->conceptos = $conceptos;
            $idUser = Auth::user()->id;
            $usuarioContribuyente = new \App\UsuarioContribuyente();
            $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $imgEmpresa = $contEmisor->rfc . DIRECTORY_SEPARATOR . "img_empresa.png";
            $exists = Storage::disk('local')->exists($imgEmpresa);
            $logoEmpresa = $storagePath . 'img_empresa.jpg';
            if ($exists) {
                $logoEmpresa = $storagePath . $imgEmpresa;
            }
            $docPdf = new \App\Pojo\DocumentoPDF();
            if ($docSession->idTipoDoc == 71) {
                $idUser = Auth::user()->id;
                $usuarioContribuyente = new \App\UsuarioContribuyente();
                $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
                $docService->getXmlDocumentoEmision($docSession, $contEmisor);
                $data = $docPdf->getDataPrevio($docSession, $contEmisor, $storagePath .'comprobanteTimbrado.xml', $storagePath .'codigoQr.jpg', $logoEmpresa);
                $view = \View::make('pdf/reciboHonorariosPrevio', compact('data'))->render();
            } else if($docSession->idTipoDoc == 75){
                $data = $docPdf->getDataPrevio($docSession, $contEmisor, $storagePath .'comprobanteTimbrado.xml', $storagePath .'codigoQr.jpg', $logoEmpresa);
                $view = \View::make('pdf/comprobantePagoPrevio', compact('data'))->render();
            }else{
                $data = $docPdf->getDataPrevio($docSession, $contEmisor, $storagePath .'comprobanteTimbrado.xml', $storagePath .'codigoQr.jpg', $logoEmpresa);
                $view = \View::make('pdf/facturaGeneralPrevio', compact('data'))->render();
            }
            //$view = \View::make('pdf/documentoPrevio', compact('data'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $filePdf=$tmp.DIRECTORY_SEPARATOR.rand()."_pervio.pdf";
            $pdf->loadHTML($view)->setPaper('a4', 'landscape')->setWarnings(false)->save($filePdf);
            $dataContent = file_get_contents($filePdf);
            $data64 = base64_encode($dataContent);
            return \Response::json($data64);
    }
    
    public function enviarEmision(\Illuminate\Http\Request $request) {
        
        $docSession = $request->session()->get('doc');
        
        $this->asignarTotales($request,false);
        //dd($request);
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $docService = new \App\Servicio\DocumentService();
        $conceptos = $docService->getListaConcepto($docSession);
        $docSession->conceptos = $conceptos;
        if($docSession->idTipoDoc == 75){
            $listPago = $docService->getListaComplementoPago($docSession);
            $docSession->pagos=$listPago;
        }
        $docSession->conceptos = $conceptos;
        $xml = $docService->getXmlDocumentoEmision($docSession, $contEmisor);
        $xmlNew = str_replace("&","&amp;",$xml);
        //dd($xml);
        Log::error('Xml Antes de Emitir');
        Log::error('****************');
        Log::error($xmlNew);
        Log::error('****************');
        $wsClient = new \App\Servicio\WebServiceClientService();
        $responce = $wsClient->sendTimbradoDocumento($xmlNew);

        if ($responce->errorCode == -1) {
            $validator = Validator::make($request->all(), [
                        'person.*.email' => 'email|unique:users',
                        'person.*.first_name' => 'required_with:person.*.last_name',
            ]);
            $validator->errors()->add('Validador SAT', $responce->errorMsg);
            return redirect('/documentos/emision/conceptosTotales')
                            ->withErrors($validator)
                            ->withInput();
        }
        $docSession->cuerpoXml = $responce->xmlTimbrado;
        $docSession->cuerpoCadenaOriginal = $responce->cadenaOriginal;
        $docSession->cuerpoQr = $responce->codigoQr;
        Storage::disk('local')->put($responce->idComprobante . 'comprobanteTimbrado.xml', $responce->xmlTimbrado);
        Storage::disk('local')->put($responce->idComprobante . 'codigoQr.jpg', $responce->codigoQr);
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $imgEmpresa = $contEmisor->rfc . DIRECTORY_SEPARATOR . "img_empresa.png";
        $exists = Storage::disk('local')->exists($imgEmpresa);
        $logoEmpresa = $storagePath . 'img_empresa.jpg';
        if ($exists) {
            $logoEmpresa = $storagePath . $imgEmpresa;
        }
        $docPdf = new \App\Pojo\DocumentoPDF();
        $data = $docPdf->getData($docSession, $contEmisor, $storagePath . $responce->idComprobante . 'comprobanteTimbrado.xml', $storagePath . $responce->idComprobante . 'codigoQr.jpg', $logoEmpresa);
        if ($docSession->idTipoDoc == 71) {
            $view = \View::make('pdf/reciboHonorarios', compact('data'))->render();
        } else if($docSession->idTipoDoc == 75){
            $view = \View::make('pdf/comprobantePago', compact('data'))->render();
        } else {
            $view = \View::make('pdf/facturaGeneral', compact('data'))->render();
        }
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $output = $pdf->output();
        $docSession->cuerpoPdf = $output;
        $docSession->uuid = $data['UUID'];
        $docSession->estatus = 'Timbrado';
        $idEmision = $docService->guardaEmision($docSession,null);
        Log::error("actualiza serie y numero " . $docSession->serie . " numero " . $docSession->numero . "  id " . $contEmisor->id);
        (new \App\SerieNumero())->updateNumeroByIdContribuyentePadreAndSerie($contEmisor->id, $docSession->serie, $docSession->numero);
        //remover
        Storage::disk('local')->delete($responce->idComprobante . 'comprobanteTimbrado.xml');
        Storage::disk('local')->delete($responce->idComprobante . 'codigoQr.jpg');
        $usuarioContribuyente->usarTimbre($contEmisor->id);
        $email = new \App\Servicio\EmailService();
        $email->sendDocumentoEmisionByQueue($idEmision);

        return view('documentos/listadoEmision/main');
    }

    public function conceptosTotales(\Illuminate\Http\Request $request) {
        $docSession = $request->session()->get('doc');
        $pathPrevio=null;
        //dd($request);
        if ($request->has('previoPath')) {
            $pathPrevio=$request->get('previoPath');
        }
        if ($docSession == null) {
            return redirect('/documentos');
        }
        if ($docSession->paso == 2) {
            if($docSession->idTipoDoc == 75){
            $validator = Validator::make($request->all(), [
                        'rfc' => ['required', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/'],
                        'razon_social' => 'required',
                        'codigo_postal' => 'required|not_in:0',
                        'uso_cfdi' => 'required|not_in:0',
                        'correo_electronico' => 'nullable|email',
                        'cel' => 'nullable|numeric',
                        'tipo_relacion' => 'required',
                        'uuid_relacionado' => 'required',
            ]);
            }else{
                $validator = Validator::make($request->all(), [
                            'rfc' => ['required', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/'],
                            'razon_social' => 'required',
                            'codigo_postal' => 'required|not_in:0',
                            'uso_cfdi' => 'required|not_in:0',
                            'correo_electronico' => 'nullable|email',
                            'cel' => 'nullable|numeric',
                ]);
            }
            if ($validator->fails()) {
                //$request->session()->put('doc', $docSession); 
                return redirect('/documentos/emision/datosGeneral')
                                ->withErrors($validator)
                                ->withInput();
            }
        }
        //dd($docSession);
        $this->asignarDatosReceptor($request);
        return $this->creaVistaTotales($docSession);
    }
    private function creaVistaTotales($docSession){
        $unidades = (new Unidad())->combo();
        $tipoImpuestos = (new TipoImpuesto())->combo();
        if ($docSession->idTipoDoc == 74) {
            return view('documentos/emision/conceptoTotales/nomina')
                            ->with('doc', $docSession)
                            ->with('listUnidad', $unidades)
                            ->with('listtipoImpuesto', $tipoImpuestos);
        }
        if ($docSession->idTipoDoc == 75) {
            $formaPagos = (new \App\FormaPago())->combo();
            return view('documentos/emision/conceptoTotales/comp_pago')
                            ->with('doc', $docSession)
                            ->with('listFormaPago', $formaPagos)
                            ->with('listUnidad', $unidades)
                            ->with('listtipoImpuesto', $tipoImpuestos);
        }
        if ($docSession->idTipoDoc == 71) {
            return view('documentos/emision/conceptoTotales/recibo_honorarios')
                            ->with('listUnidad', $unidades)
                            ->with('doc', $docSession)
                            ->with('listtipoImpuesto', $tipoImpuestos);
        }
        return view('documentos/emision/conceptoTotales/fac_electronica')
                        ->with('doc', $docSession)
                        ->with('listUnidad', $unidades)
                        ->with('listtipoImpuesto', $tipoImpuestos);
    }
    public function emisionDatoGeneral(\Illuminate\Http\Request $request) {
        $docSession = $request->session()->get('doc');
        if ($docSession == null) {
            return redirect('/documentos');
        }
        if ($docSession->paso == 1) {
            $validator = Validator::make($request->all(), [
                        'fecha_emision' => 'required|max:255',
                        'forma_pago' => 'required|not_in:0',
                        'tipo_cambio' => 'required|max:255',
                        'moneda' => 'required|not_in:0',
                        'metodo_pago' => 'required|not_in:0',
                        'tipo_comprobante' => 'required|not_in:0',
                        'condiciones_pago' => 'max:700',
                        'tipoDoc' => 'required|not_in:0',
            ]);
            if ($validator->fails()) {
                $eId = Crypt::encrypt('1');
                return redirect('/documentos/tipo/' . $eId)
                                ->withErrors($validator)
                                ->withInput();
            }
        }
        $this->asignarDatosGenerales($request);
        $doc = (new TipoDocumento())->getById($docSession->idTipoDoc);
        $docSession->tipoDoc = $doc->nombre;
        $paises = (new Pais())->getComboByPais();
        $usoCfdis = (new \App\UsoCfdi())->combo();
        $estados = (new Estado())->getComboByPais(1);
        $tipoRelaciones = (new \App\TipoRelacion())->combo();
        return view('documentos/emision/datos_receptor')
                        ->with('listPais', $paises)
                        ->with('listEstado', $estados)
                        ->with('listUsoCfdi', $usoCfdis)
                        ->with('tipoRelaciones', $tipoRelaciones)
                        ->with('doc', $docSession)
                        ->with('isCompra', false)
                        ->with('docNombre', $doc->nombre);
    }

    private function asignarDatosReceptor(\Illuminate\Http\Request $request) {
        $email = $request->get('correo_electronico');
        $cell = $request->get('cel');
        $usoCfdi = $request->get('uso_cfdi');
        $tipoRelacion = $request->get('tipo_relacion');
        $uuidRelacionado = $request->get('uuid_relacionado');
        $rfc = strtoupper($request->get('rfc'));
        $razonSocial = $request->get('razon_social'); //strtoupper($request->get('razon_social'));
        $pais = $request->get('pais');
        $cmbEstado = $request->get('cmbEstado');
        $estado = $request->get('estado');
        $cmbMunicipio = $request->get('cmbMunicipio');
        $municipio = $request->get('municipio');
        $cmbColonia = $request->get('cmbColonia');
        $colonia = $request->get('colonia');
        $codigoPostal = $request->get('codigo_postal');
        $calle = strtoupper($request->get('calle'));
        $numExt = strtoupper($request->get('num_ext'));
        $numInt = strtoupper($request->get('num_int'));
        $localidad = $request->get('localidad');
        $doc = $request->session()->get('doc');
        if ($doc == null) {
            $doc = new \App\Pojo\DocumentoEmision();
        }
        $doc->paso = 3;
        $doc->email = ($email != null ? $email : $doc->email);
        $doc->cell = ($cell != null ? $cell : $doc->cell);
        $doc->rfc = ($rfc != null ? $rfc : $doc->rfc);
        $doc->usoCfdi = ($usoCfdi != null ? $usoCfdi : $doc->usoCfdi);
        $doc->tipoRelacion = ($tipoRelacion != null ? $tipoRelacion : $doc->tipoRelacion);
        $doc->uuidRelacionado = ($uuidRelacionado != null ? $uuidRelacionado : $doc->uuidRelacionado);
        $doc->razonSocial = ($razonSocial != null ? $razonSocial : $doc->razonSocial);
        $doc->pais = ($pais != null ? $pais : $doc->pais);
        $doc->cmbEstado = ($cmbEstado != null ? $cmbEstado : $doc->cmbEstado);
        $doc->estado = ($estado != null ? $estado : $doc->estado);
        $doc->cmbMunicipio = ($cmbMunicipio != null ? $cmbMunicipio : $doc->cmbMunicipio);
        $doc->municipio = ($municipio != null ? $municipio : $doc->municipio);
        $doc->cmbColonia = ($cmbColonia != null ? $cmbColonia : $doc->cmbColonia);
        $doc->colonia = ($colonia != null ? $colonia : $doc->colonia);
        $doc->codigoPostal = ($codigoPostal != null ? $codigoPostal : $doc->codigoPostal);
        $doc->calle = ($calle != null ? $calle : $doc->calle);
        $doc->numExt = ($numExt != null ? $numExt : $doc->numExt);
        $doc->numInt = ($numInt != null ? $numInt : $doc->numInt);
        $doc->localidad = ($localidad != null ? $localidad : $doc->localidad);
        $request->session()->put('doc', $doc);
        $docTmp = $this->actualizaEmisionTmp($doc, null);
        $request->session()->put('doc', $docTmp);
    }

    public function getImpuestoJson(\Illuminate\Http\Request $request) {
        if (Session::token() != Request::header('X-CSRF-Token')) {
            throw new Illuminate\Session\TokenMismatchException;
        }
        $tipoImpuesto = $request->get('tipo_impuesto');
        $impuestos = (new Impuesto())->getByTipoImpuesto($tipoImpuesto);
        return \Response::json($impuestos);
    }
    
    public function getDocumentoUuidJson(\Illuminate\Http\Request $request) {
        /*if (Session::token() != Request::header('X-CSRF-Token')) {
            throw new Illuminate\Session\TokenMismatchException;
        }*/
        $uuid = $request->get('uuid');
        $emision = new \App\Emision();
        $doc=$emision->geDocumentoByUuid($uuid);
        //$impuestos = (new Impuesto())->getByTipoImpuesto($tipoImpuesto);
        return \Response::json($doc);
    }
    
    public function getDocumentoToServer($idDocumento) {
        //$idDocumento = $request->get('idDocumento');
        $archivo = new \App\Archivo();
        $doc = $archivo->getById($idDocumento);
        Storage::disk('local')->put($doc->nombre . '.' . $doc->extension, $doc->cuerpo);
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $pathToFile = $storagePath . $doc->nombre . '.' . $doc->extension;
        return $pathToFile;
    }

    public function testFactura(\Illuminate\Http\Request $request) {
        //$doc = $request->session()->get('doc');
        $idEmision = $request->get('idEmision');
        $emisionDB = new \App\Emision();
        $emi = $emisionDB->getById($idEmision);

        $cont = new \App\Contribuyente();
        $cont->getContribuyenteById($emi);
        $doc = new \App\Pojo\DocumentoEmision();

        $docPdf = new \App\Pojo\DocumentoPDF();
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $listaCooncepto = array();
        $concepto = new \App\Pojo\Concepto();
        $concepto->codigo = null;
        $concepto->nombre = "Prueba descripcion";
        $concepto->identificacion = null;
        $concepto->cantidad = 5;
        $concepto->idUnidad = 2;
        $concepto->unidadNom = "Barril";
        $concepto->predial = null;
        $concepto->precioUnitario = 12000;
        $concepto->importe = 60000;
        array_push($listaCooncepto, $concepto);
        $doc->conceptos = $listaCooncepto;
        $doc->email = 'caifandf84@gmail.com';
        $data = $docPdf->getData($doc, $contEmisor, $storagePath . '137363comprobanteTimbrado.xml', $storagePath . "997344codigoQr.jpg", $storagePath . 'img_factura.png');
        //dd($data);
        Log::error('Antes de enviar correo Job');
        $emailServ = new \App\Servicio\EmailService();
        $emailServ->sendDocumentoEmisionByQueue(1);
        /* Mail::send('email.doc_emision_test',['doc'=>$doc],function ($message) {
          $message->from('factura@conuxi.com');
          $message->to('caifandf84@gmail.com')
          ->subject('Comprobante emisión');
          }); */
        Log::error('despues de enviar correo Job');
        return view('pdf/facturaGeneral')->with(compact('data'));
    }

    public function testValores() {
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $isEmisior = $usuarioContribuyente->isContribuyenteEmisor(3);
        Log::error('$isEmisior' . $isEmisior);
        if ($isEmisior) {
            dd("estoy en True");
        } else {
            dd("Estoy en False");
        }
    }

    public function getInfacFormulario() {
        $doc = new \App\Pojo\DocumentoEmision();
        $paises = (new \App\Pais())->getComboByPais();
        $usoCfdis = (new \App\UsoCfdi())->combo();
        $estados = (new Estado())->getComboByPais(1);
        $tipoRelaciones = (new \App\TipoRelacion())->combo();
        $tipoDocs = (new \App\TipoDocumento())->get();
        $metodoPagos = (new \App\MetodoPago())->combo();
        $formaPagos = (new \App\FormaPago())->combo();
        $monedas = (new \App\Moneda())->combo();
        $tipoComprobante = (new \App\TipoComprobante())->combo();
        $unidades = (new \App\Unidad())->combo();
        $tipoImpuestos = (new \App\TipoImpuesto())->combo();
        return view('documentos/emision/infac/main')
                        ->with('listPais', $paises)
                        ->with('listEstado', $estados)
                        ->with('listUsoCfdi', $usoCfdis)
                        ->with('tipoRelaciones', $tipoRelaciones)
                        ->with('doc', $doc)
                        ->with('isCompra', false)
                        ->with('docNombre', "Emision")
                        ->with('tipoDocs', $tipoDocs)
                        ->with('listMetodoPago', $metodoPagos)
                        ->with('listFormaPago', $formaPagos)
                        ->with('listTipoComprobante', $tipoComprobante)
                        ->with('listMoneda', $monedas)
                        ->with('listUnidad', $unidades)
                        ->with('listtipoImpuesto', $tipoImpuestos);
    }

}
