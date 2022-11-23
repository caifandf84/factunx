<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentoEmisionWSController extends Controller
{
    //
    public function __construct() {
        $this->middleware('guest');
    }
    
    public function test(){
        return \Response::json(['errorCode' => '2','errorMensaje'=>'Se requiere email correcto']);
    }
    
    public function generaDocumento(Request $request){
        $key = $request->header('keyAccess');
        $email = $request->header('user');
        
        if($email==null)
            return \Response::json(['errorCode' => '2','errorMensaje'=>'Se requiere email correcto']);
        if($email!='wsonline1239@factunx.com' && $email!='sitrackgcp@sitrack.com')
            return \Response::json(['errorCode' => '3','errorMensaje'=>'Se requiere email valido']);
        if($key==null)
            return \Response::json(['errorCode' => '1','errorMensaje'=>'Se requiere keyAccess']);
        $userDao=new \App\User();
        $user=$userDao->getUserByCorreo($email);
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($user->id);
        //return \Response::json(['errorCode' => $request->all()]);
        $doc=$this->requestToDocument($request);
        $docService = new \App\Servicio\DocumentService();
        
        $conceptos = $docService->getListaConcepto($doc);
        $doc->conceptos = $conceptos;
        
        $xml = $docService->getXmlDocumentoEmision($doc, $contEmisor);
        Log::error('Xml Antes de Emitir');
        Log::error('****************');
        Log::error($xml);
        Log::error('****************');
        $wsClient = new \App\Servicio\WebServiceClientService();
        $responce = $wsClient->sendTimbradoDocumento($xml);
        if ($responce->errorCode == -1) {
            return \Response::json(['errorCode' => '3','errorMensaje'=>$responce->errorMsg]);
        }
        $doc->cuerpoXml = $responce->xmlTimbrado;
        $doc->cuerpoCadenaOriginal = $responce->cadenaOriginal;
        $doc->cuerpoQr = $responce->codigoQr;
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
        $data = $docPdf->getData($doc, $contEmisor, $storagePath . $responce->idComprobante . 'comprobanteTimbrado.xml', $storagePath . $responce->idComprobante . 'codigoQr.jpg', $logoEmpresa);

        if ($doc->idTipoDoc == 71) {
            $view = \View::make('pdf/reciboHonorarios', compact('data'))->render();
        } else {
            $view = \View::make('pdf/facturaGeneral', compact('data'))->render();
        }
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $output = $pdf->output();
        $doc->cuerpoPdf = $output;
        $doc->uuid = $data['UUID'];
        $doc->estatus = 'Timbrado';
        $idEmision = $docService->guardaEmision($doc,$user->id);
        Log::error("actualiza serie y numero " . $doc->serie . " numero " . $doc->numero . "  id " . $contEmisor->id);
        (new \App\SerieNumero())->updateNumeroByIdContribuyentePadreAndSerie($contEmisor->id, $doc->serie, $doc->numero);
        //remover
        Storage::disk('local')->delete($responce->idComprobante . 'comprobanteTimbrado.xml');
        Storage::disk('local')->delete($responce->idComprobante . 'codigoQr.jpg');
        $usuarioContribuyente->usarTimbre($contEmisor->id);
        $emailService = new \App\Servicio\EmailService();
        $emailService->sendDocumentoEmisionByQueue($idEmision);
        return \Response::json(['errorCode' => '200','errorMensaje'=>'ok','uuid'=>$doc->uuid,'identificador'=>$idEmision,'status'=>$doc->estatus]);
    }
    
    public function obtenerDocumento(Request $request){
        $key = $request->header('keyAccess');
        $email = $request->header('user');
        $identificador=$request->get('identificador');
        if($email==null)
            return \Response::json(['errorCode' => '2','errorMensaje'=>'Se requiere email correcto']);
        if($key==null)
            return \Response::json(['errorCode' => '1','errorMensaje'=>'Se requiere keyAccess']);
        $emi=new \App\Emision();
        $objEmision=$emi->getById($identificador);
        $base64Pdf = base64_encode($objEmision->cuerpoPdf);
        $base64Xml = base64_encode($objEmision->cuerpoXml);
        return \Response::json(['errorCode' => '200','errorMensaje'=>'ok','xml'=>$base64Xml,'pdf'=>$base64Pdf]);
    }
    
    private function requestToDocument(\Illuminate\Http\Request $request){
        $doc = new \App\Pojo\DocumentoEmision();
        $doc=$this->asignarDatosGenerales($request,$doc);
        $doc=$this->asignarDatosReceptor($request,$doc);
        $doc=$this->asignarTotales($request,$doc);
        return $doc;
    }
    
    /**
     * Paso 1 
     * @param Request $request
     */
    private function asignarDatosGenerales(\Illuminate\Http\Request $request,\App\Pojo\DocumentoEmision $doc) {
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
        
        //$doc = new \App\Pojo\DocumentoEmision();

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
        
        return $doc;
    }
    
    /**
     * Paso 2
     * @param Request $request
     * @param \App\Pojo\DocumentoEmision $doc
     * @return \App\Pojo\DocumentoEmision
     */
    private function asignarDatosReceptor(\Illuminate\Http\Request $request,\App\Pojo\DocumentoEmision $doc) {
        $email = $request->get('correo_electronico');
        $cell = $request->get('cel');
        $usoCfdi = $request->get('uso_cfdi');
        $tipoRelacion = $request->get('tipo_relacion');
        $uuidRelacionado = $request->get('uuid_relacionado');
        $rfc = strtoupper($request->get('rfc'));
        $razonSocial = $request->get('razon_social');
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
        return $doc;
    }
    
    /**
     * Paso 3
     * @param Request $request
     * @param \App\Pojo\DocumentoEmision $doc
     * @return \App\Pojo\DocumentoEmision
     */
    private function asignarTotales(\Illuminate\Http\Request $request,\App\Pojo\DocumentoEmision $doc) {
        $descuento = $request->get('descuento_tot');
        $descDescuento = $request->get('desc_descuento_tot');
        $otroImporteRet = $request->get('otro_imp_ret_tot');
        $otroImporteTras = $request->get('otro_imp_tras_tot');
        $importeRet = $request->get('total_imp_ret_tot');
        $importeTras = $request->get('total_imp_tras_tot');
        $subtotal = $request->get('sub_total_tot');
        $total = $request->get('total_tot');
        $listConcepto = $request->get('conceptos');
        
        $listaCooncepto=array();
        foreach ($listConcepto as $concepto) {
            foreach ($concepto as $c) {
                array_push($listaCooncepto,$c);
            }
                    
        }
        $listImpuesto = $request->get('impuestos');
        $listaImpuesto=array();
        foreach ($listImpuesto as $impuesto) {
            foreach ($impuesto as $i) {
                array_push($listaImpuesto,$i);
            }         
        }
        
        $pedimentos = $request->get('pedimentos');
        $totalImporteRet = $request->get('total_imp_ret_tot');
        $totalImporteTras = $request->get('total_imp_tras_tot');
        $porcTasaImp = $request->get('porc_tasa_imp');

        $doc->paso = 0;
        $doc->pedimentos = $pedimentos;
        $doc->impuestos = $listaImpuesto;
        $doc->conceptos = $listaCooncepto;
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
        
        return $doc;

    }
    
}
