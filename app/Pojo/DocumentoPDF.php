<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Pojo;
use DB;
/**
 * Description of DocumentoPDF
 *
 * @author Armando
 */
class DocumentoPDF {
    //put your code here
    
    public function getDataOxxoReferencia($producto,$referencia,$oxxopay_brand){
        $data =  [
            'producto'=> $producto,
            'referencia1'=> substr($referencia,0,4),
            'referencia2'=> substr($referencia,4,4),
            'referencia3'=> substr($referencia,8,4),
            'referencia4'=> substr($referencia,12,2),
            'imgOxxopayBrand' => $oxxopay_brand
        ];
        return $data;
    }
    
    public function getDataPrevio(DocumentoEmision $doc,$emisor,$pathFileXml,$pathFileQr,$pathFileEmpresa){
        $tipoRelacion=null;
        if($doc->tipoRelacion!=null && $doc->tipoRelacion!=0){
            $tipoRelacion=(new \App\TipoRelacion())->getById($doc->tipoRelacion);
        }
        if($doc->idFormaPago!=null && $doc->idFormaPago!=0){
            $formaDePago=(new \App\FormaPago())->getNombreById($doc->idFormaPago);
        }
        if($doc->metodoPago!=null && $doc->metodoPago!=''){
            $metodoPago=(new \App\MetodoPago())->getNombreById($doc->metodoPago);
        }
        $usoCfdi=(new \App\UsoCfdi())->getById($doc->usoCfdi);
        $tipoComprobante=(new \App\TipoComprobante())->getById($doc->tipoComprobante);
        
	if ($doc->idTipoDoc == 71) {
            $totales=$doc->totales;
        }else{
            $totales='';
        }
        $data =  [
            'tipoDocumento'=> $tipoComprobante->nombre,
            'razonSocialEmisor'=> $emisor->razon_social,
            'rfcEmisor'=> $emisor->rfc,
            'regimenFiscalEmisor'=>$emisor->regimen_fiscal,
            'noCertificado'=> '',
            'lugarExpedicion'=> '',
            'fechaTimbrado'=> '',
            'noCertificadoSAT'=> '',
            'UUID'=> '',
            'formaDePago'=>$formaDePago,
            'metodoPago'=>$metodoPago,
            'razonSocialReceptor'=> $doc->razonSocial,
            'rfcReceptor'=> $doc->rfc,
            'correoReceptor'=> $doc->email,
            'codigoPostalReceptor'=> $doc->codigoPostal,
            'conceptos'=>$doc->conceptos,
            'totales'=>$totales,
            'descuento'=>$doc->descuento,
            'uuidRelacionado'=>$doc->uuidRelacionado,
            
            'tipoRelacion'=>($tipoRelacion!=null?$tipoRelacion->nombre:""),//descripcion
            'usoCFDI'=>$usoCfdi->nombre,//descripcion
            'tipoComprobante'=>$tipoComprobante->nombre,//descripcion
            'subTotal'=>$doc->subtotal,
            'porcTasaImp'=>$doc->porcTasaImp,
            'totalImporte'=>$doc->totalImporte,
            'total'=>$doc->total,
            'selloCFD'=>'',
            'selloSAT'=>'',
            //'conceptos'=>$doc->conceptos,
            'qr'=>$pathFileEmpresa,
            'imgEmpresa'=>$pathFileEmpresa,
            'cadenaOriginal'=>$doc->cuerpoCadenaOriginal,
            'condicionesPago'=>$doc->condicionesPago,
            'serie'=>$doc->serie,
            'numero'=>$doc->numero,
            'pagos'=>$doc->pagos,
            'moneda'=>($doc->moneda!=null?$doc->moneda:""),
            'tipoCambio'=>($doc->tipoCambio!=null?$doc->tipoCambio:"")
        ];
        return $data;
    }
    
    public function getData(DocumentoEmision $doc,$emisor,$pathFileXml,$pathFileQr,$pathFileEmpresa){
        $xml=$this->getParameterXML($pathFileXml);
        $tipoRelacion=null;
        if($doc->tipoRelacion!=null && $doc->tipoRelacion!=0){
            $tipoRelacion=(new \App\TipoRelacion())->getById($doc->tipoRelacion);
        }
        if($doc->idFormaPago!=null && $doc->idFormaPago!=0){
            $formaDePago=(new \App\FormaPago())->getNombreById($doc->idFormaPago);
        }
        if($doc->metodoPago!=null && $doc->metodoPago!=''){
            $metodoPago=(new \App\MetodoPago())->getNombreById($doc->metodoPago);
        }
        $usoCfdi=(new \App\UsoCfdi())->getById($doc->usoCfdi);
        $tipoComprobante=(new \App\TipoComprobante())->getById($doc->tipoComprobante);
        $data =  [
            'razonSocialEmisor'=> $emisor->razon_social,
            'rfcEmisor'=> $emisor->rfc,
            'regimenFiscalEmisor'=>$emisor->regimen_fiscal,
            'noCertificado'=> $xml->noCertificado,
            'lugarExpedicion'=> $xml->lugarExpedicion,
            'fechaTimbrado'=> $xml->fechaTimbrado,
            'noCertificadoSAT'=> $xml->noCertificadoSAT,
            'UUID'=> $xml->UUID,
            'formaDePago'=>$formaDePago,
            'metodoPago'=>$metodoPago,
            'razonSocialReceptor'=> $doc->razonSocial,
            'rfcReceptor'=> $doc->rfc,
            'correoReceptor'=> $doc->email,
            'codigoPostalReceptor'=> $doc->codigoPostal,
            'conceptos'=>$doc->conceptos,
            'totales'=>$doc->totales,
            'descuento'=>$doc->descuento,
            'uuidRelacionado'=>$doc->uuidRelacionado,
            
            'tipoRelacion'=>($tipoRelacion!=null?$tipoRelacion->nombre:""),//descripcion
            'usoCFDI'=>$usoCfdi->nombre,//descripcion
            'tipoComprobante'=>$tipoComprobante->nombre,//descripcion
            'subTotal'=>$doc->subtotal,
            'porcTasaImp'=>$doc->porcTasaImp,
            'totalImporte'=>$doc->totalImporte,
            'total'=>$doc->total,
            'selloCFD'=>$xml->selloCFD,
            'selloSAT'=>$xml->selloSAT,
            //'conceptos'=>$doc->conceptos,
            'qr'=>$pathFileQr,
            'imgEmpresa'=>$pathFileEmpresa,
            'cadenaOriginal'=>$doc->cuerpoCadenaOriginal,
            'condicionesPago'=>$doc->condicionesPago,
            'serie'=>$doc->serie,
            'numero'=>$doc->numero,
            'pagos'=>$doc->pagos,
            'moneda'=>($doc->moneda!=null?$doc->moneda:""),
            'tipoCambio'=>($doc->tipoCambio!=null?$doc->tipoCambio:"")
        ];
        return $data;
    }
    
    private function getParameterXML($pathFileXml){
        $xml=simplexml_load_file($pathFileXml);
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);
        $responce = new \stdClass();
        foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
            $responce->noCertificado=((String) $cfdiComprobante['NoCertificado']);
            $responce->fecha=((String) $cfdiComprobante['Fecha']);
            $responce->lugarExpedicion=((String) $cfdiComprobante['LugarExpedicion']);
        }
        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $responce->selloCFD=((String) $tfd['SelloCFD']); 
            $responce->fechaTimbrado=((String) $tfd['FechaTimbrado']); 
            $responce->UUID=((String) $tfd['UUID']); 
            $responce->noCertificadoSAT=((String) $tfd['NoCertificadoSAT']); 
            $responce->selloSAT=((String) $tfd['SelloSAT']); 
         }
        return $responce;
    }    
}
