<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Servicio;

/**
 * Description of DocumentService
 *
 * @author Armando
 */
use DateTime;
use Auth;
use Illuminate\Support\Facades\Log;
class DocumentService {
    //put your code here

    
    
    public function getXmlDocumentoEmision(\App\Pojo\DocumentoEmision $param, $emisor) {
        
        $xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xml.=$this->getComprobanteHeader($param, $emisor);
        $xml.="<cfdi:Emisor Nombre=\"".$emisor->razon_social."\" ";
        $xml.="RegimenFiscal=\"".$emisor->id_regimen_fiscal."\" Rfc=\"".$emisor->rfc."\" /> \n";
        if($param->idTipoDoc != 75){
            $xml.=$this->getRelacionados($param);
        }
        $xml.=$this->getReceptor($param);
        $xml.=$this->getConceptos($param);
        if($param->idTipoDoc != 75){
            if($param->idTipoDoc == 71){
                $listConceptos=$param->conceptos;
                foreach ($listConceptos as $concepto) {
                    $xml.=$this->getImpuestoConcepto($param,$concepto,true);
                }
            }else{
                $xml.=$this->getImpuestoConcepto($param);
            }
        }else{
            $xml.=$this->getComplementoPago($param);
        }
        $xml.="</cfdi:Comprobante>";
        Log::info("Termino getXmlDocumentoEmision()");
        return $xml;
    }
    
    public function getComprobanteHeader(\App\Pojo\DocumentoEmision $param, $emisor){
        $fechaEmision = DateTime::createFromFormat('d/m/Y H:i', $param->fechaEmision);
        $xml="<cfdi:Comprobante \n";
        if($param->idTipoDoc != 75){
            $xml.="xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd\" \n";
            if($param->condicionesPago!=null && $param->condicionesPago!=""){
                $xml.=" CondicionesDePago=\"".$param->condicionesPago."\" \n";
            }
            $xml.="Descuento=\"".sprintf("%.2f",$param->descuento)."\" \n";
            $xml.="Fecha=\"".$fechaEmision->format('Y-m-d')."T".$fechaEmision->format('H:i:s')."\" \n";
            if($param->numero!= null && $param->numero!=""){
                $xml.="Folio=\"".$param->numero."\" \n";
            }
            $xml.="FormaPago=\"".str_pad($param->idFormaPago,2,"0",STR_PAD_LEFT)."\" \n";
            Log::info("LugarExpedicion".$emisor->codigo_postal);
            $xml.="LugarExpedicion=\"".str_pad($emisor->codigo_postal,5,"0",STR_PAD_LEFT)."\" \n";
            $xml.="MetodoPago=\"".$param->metodoPago."\" \n";
            $xml.="Moneda=\"".$param->moneda."\" \n";
            if($param->serie!=null && $param->serie!=""){
                $xml.="Serie=\"".$param->serie."\" \n";
            }
            $xml.="SubTotal=\"".$this->truncate($param->subtotal,2)."\" \n";
            $xml.="TipoCambio=\"".$param->tipoCambio."\" \n";
            $xml.="TipoDeComprobante=\"".$param->tipoComprobante."\" \n";
            $xml.="Total=\"".$this->truncate($param->total,2)."\" \n";
            $xml.="Version=\"3.3\" xmlns:cfdi=\"http://www.sat.gob.mx/cfd/3\"> \n";
            
        }else{
            $xml.=" xsi:schemaLocation=\"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd "
            ."http://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd\" "
            ."xmlns:cfdi=\"http://www.sat.gob.mx/cfd/3\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
            ."xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:pago10=\"http://www.sat.gob.mx/Pagos\" Version=\"3.3\" ";
            if($param->serie!=null && $param->serie!=""){
                $xml.="Serie=\"".$param->serie."\" \n";
            }
            if($param->numero!= null && $param->numero!=""){
                $xml.="Folio=\"".$param->numero."\" \n";
            }
            $xml.="Fecha=\"".$fechaEmision->format('Y-m-d')."T".$fechaEmision->format('H:i:s')."\" \n";
            $xml.="TipoDeComprobante=\"".$param->tipoComprobante."\" \n";
            $xml.="LugarExpedicion=\"".str_pad($emisor->codigo_postal,5,"0",STR_PAD_LEFT)."\" \n";
            $xml.="SubTotal=\"0\" Moneda=\"XXX\" Total=\"0\"  > \n";   
        }
        return $xml;
    }
    
    
    public function guardaEmision(\App\Pojo\DocumentoEmision $values, $idUsuario = null){
            $random = rand(5, 999999);
            if($idUsuario!=null){
                $idUser=$idUsuario;
            }else{
                $idUser=Auth::user()->id;
            }
            $usuarioContribuyente=new \App\UsuarioContribuyente();
            $contribuyente=new \App\Contribuyente();
            $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
            $emiValidador=$contribuyente->getContribuyenteByRFC($values->rfc);
            if($emiValidador!=null){
                $isEmisior=$usuarioContribuyente->isContribuyenteEmisor($emiValidador->id);
                if($isEmisior){
                    $contReceptor=$emiValidador;
                }else{
                    $contReceptor=$contribuyente->guardaOActualizar($values); 
                }
            }else{
                $contReceptor=$contribuyente->guardaOActualizar($values);
            }
            $archivo=new \App\Archivo();
            $arcXml=$archivo->guardar($values->cuerpoXml,$random."_timbrado","xml");
            $arcPdf=$archivo->guardar($values->cuerpoPdf,$random."_timbrado","pdf");
            $arcJpg=$archivo->guardar($values->cuerpoQr,$random."_imagen_qr","jpg");
            $arcTxt=$archivo->guardar($values->cuerpoCadenaOriginal,$values->fechaEmision."_cadena_org","txt");
            $emision=new \App\Emision();
            $emision->serie=$values->serie;
            $emision->numero=$values->numero;
            $dateTime=\DateTime::createFromFormat("d/m/Y H:i", $values->fechaEmision);
            $carbon= \Carbon\Carbon::createFromTimestamp($dateTime->getTimestamp());
            $emision->fecha_emision=$carbon;
            $emision->monto=$values->total;
            $emision->tipo_de_cambio=$values->tipoCambio;
            
            $emision->condicion_de_pago=$values->condicionesPago;
            $emision->id_moneda=$values->moneda;
            $emision->id_forma_de_pago=$values->idFormaPago;
            $emision->descuento=$values->descuento;
            $emision->desc_descuento=$values->descDescuento;
            $emision->uuid=$values->uuid;
            $emision->estatus=$values->estatus;
            
            $emision->otro_importe_ret=$values->otroImporteRet;
            $emision->importe_ret=$values->importeRet;
            $emision->otro_importe_tras=$values->otroImporteTras;
            $emision->importe_tras=$values->importeTras;
            $emision->subtotal=$values->subtotal;
            $emision->total=$values->total;
            $emision->porcentaje_tasa_imp=$values->porcTasaImp;
            $emision->id_uso_cfdi=$values->usoCfdi;        
            $emision->id_tipo_comprobante=$values->tipoComprobante; 
            
            $emision->id_tipo_documento=$values->idTipoDoc;
            $emision->id_metodo_de_pago=$values->metodoPago;
            $emision->id_contribuyente_emisor=$contEmisor->id;
            $emision->id_contribuyente_receptor=$contReceptor->id;
            $emision->id_archivo_xml=$arcXml->id;
            $emision->id_archivo_pdf=$arcPdf->id;
            $emision->id_archivo_qr=$arcJpg->id;
            $emision->id_archivo_cadena_original=$arcTxt->id;
            $emision->save();
            return $emision->id;
    }
    
    private function truncate($val, $f="0")
    {
        if(($p = strpos($val, '.')) !== false) {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }
        return $val;
    }
    
    private function getRelacionados(\App\Pojo\DocumentoEmision $param){
        $xml="";
        if($param->tipoRelacion!=0 && $param->tipoRelacion!=null){
            $tipoRelacion=(new \App\TipoRelacion())->getById($param->tipoRelacion);
            $xml.="<cfdi:CfdiRelacionados TipoRelacion=\"".$tipoRelacion->id_sat."\"> \n";
            $xml.="<cfdi:CfdiRelacionado UUID=\"".$param->uuidRelacionado."\" /> \n";
            $xml.="</cfdi:CfdiRelacionados> \n";
        }
        return $xml;
    }
    
    private function getReceptor(\App\Pojo\DocumentoEmision $param) {
        $xml="<cfdi:Receptor Nombre=\"".$param->razonSocial."\" ";
        $environment = \App::environment();
        if ($environment != 'Production') {
            $xml.="Rfc=\"AAA010101AAA\" UsoCFDI=\"".$param->usoCfdi."\" /> \n";
        }else{
            $xml.="Rfc=\"".$param->rfc."\" UsoCFDI=\"".$param->usoCfdi."\" /> \n";
        }
        return $xml;
    }
    
    private function getConceptos(\App\Pojo\DocumentoEmision $param) {
        
        $listConceptos=$param->conceptos;
        $xml="<cfdi:Conceptos>\n";
        foreach ($listConceptos as $concepto) {
            Log::error("concepto->idUnidad".$concepto->idUnidad);
            $unidadDB=(new \App\Unidad())->getById($concepto->idUnidad);
            $xml.="<cfdi:Concepto Cantidad=\"".$concepto->cantidad."\" ";
            $xml.="ClaveProdServ=\"".$concepto->idSatProductoServicio."\" ";
            $xml.="ClaveUnidad=\"".$unidadDB->id_sat."\" "; 
            $xml.="Descripcion=\"".$concepto->nombre."\" ";
            if($concepto->descuento!=null && $concepto->descuento!="" && $param->idTipoDoc != 75){
                $xml.="Descuento=\"".sprintf("%.2f",$concepto->descuento)."\" ";
            }else if($param->idTipoDoc != 75) {
                $xml.="Descuento=\"0\" ";
            }
            $xml.="Importe=\"".sprintf("%.2f",$concepto->importe)."\" ";
            if($param->idTipoDoc != 75){
                $xml.="Unidad=\"".$unidadDB->id_sat."\" ";
            }
            $xml.="ValorUnitario=\"".sprintf("%.2f",$concepto->precioUnitario)."\" > ";
            if($param->idTipoDoc != 75){
                $xml.=$this->getImpuestoConcepto($param,$concepto);
            }
            $xml.="</cfdi:Concepto>\n";
        }
        $xml.="</cfdi:Conceptos>\n";
        
        return $xml;
    }
    
    private function getComplementoPago(\App\Pojo\DocumentoEmision $param){
        $xml="<cfdi:Complemento> \n";
        $xml.="<pago10:Pagos Version=\"1.0\"> \n";
        $listComplementos=$param->pagos;
        foreach ($listComplementos as $complemento) {
            $fechas = explode(' ', $complemento->fechaPago);
            $fecha = explode('/',$fechas[0]);
            $xml.="<pago10:Pago FechaPago=\"".$fecha[2]."-".$fecha[1]."-".$fecha[0]."T12:00:00\" ";
            $xml.="FormaDePagoP=\"".str_pad($complemento->formaDePagoP,2,"0",STR_PAD_LEFT)."\" MonedaP=\"".$complemento->monedaP."\" ";
            $xml.="Monto=\"".$complemento->monto."\"> \n";
            $xml.="<pago10:DoctoRelacionado IdDocumento=\"".$complemento->idDocumento."\" ";
            if($complemento->folio!=null && $complemento->folio!=''){
                $xml.=" Folio=\"".$complemento->folio."\"  ";
            }
            if($complemento->serie!=null && $complemento->serie!=''){
                $xml.=" Serie=\"".$complemento->serie."\"  ";
            }
            if($complemento->rFCEmisorCtaBeneficiaria!=null && $complemento->rFCEmisorCtaBeneficiaria!=''){
                $xml.=" RfcEmisorCtaBen=\"".$complemento->rFCEmisorCtaBeneficiaria."\"  ";
            }
            if($complemento->ctaBeneficiaria!=null && $complemento->ctaBeneficiaria!=''){
                $xml.=" CtaBeneficiario=\"".$complemento->ctaBeneficiaria."\"  ";
            }
            if($complemento->nombreBanco!=null && $complemento->nombreBanco!=''){
                $xml.=" NomBancoOrdExt=\"".$complemento->nombreBanco."\"  ";
            }
            if($complemento->rFCEmisorCtaOrigen!=null && $complemento->rFCEmisorCtaOrigen!=''){
                $xml.=" RfcEmisorCtaOrd=\"".$complemento->rFCEmisorCtaOrigen."\"  ";
            }
            $xml.="MonedaDR=\"".$complemento->monedaDR."\" MetodoDePagoDR=\"".$complemento->metodoDePagoDR."\" ";
            $xml.="NumParcialidad=\"".$complemento->numParcialidad."\" ImpSaldoAnt=\"".$complemento->impSaldoAnt."\" ";
            $xml.="ImpPagado=\"".$complemento->impPagado."\" ImpSaldoInsoluto=\"".$complemento->impSaldoInsoluto."\"/> \n";
            $xml.="</pago10:Pago> \n";
        }
        $xml.="</pago10:Pagos> \n";
        $xml.="</cfdi:Complemento> \n";
        
        return $xml;
    }
    
    /**
     * en caso de factura todos los conceptos tiene el mismo impuesto
     */
    private function getImpuestoConcepto(\App\Pojo\DocumentoEmision $param, \App\Pojo\Concepto $concepto=null,$isFinal=null){
        /*if($isFinal==true){
            $xml="<cfdi:Impuestos TotalImpuestosRetenidos=\"1033.33\" TotalImpuestosTrasladados=\"800.00\" >\n";
            $xml.="<cfdi:Retenciones>\n";
            $xml.="<cfdi:Retencion Importe=\"533.33\" Impuesto=\"002\" />\n";
            $xml.="<cfdi:Retencion Importe=\"500.00\" Impuesto=\"001\" />\n";
            $xml.="</cfdi:Retenciones>\n";
            $xml.="<cfdi:Traslados>\n";
            $xml.="<cfdi:Traslado Importe=\"800.00\" Impuesto=\"002\" TasaOCuota=\"0.160000\" TipoFactor=\"Tasa\" />\n";
            $xml.="</cfdi:Traslados>\n";
            $xml.="</cfdi:Impuestos>\n";
            return $xml;
            
        }*/
        $xml="<cfdi:Impuestos ";
        if($concepto==null || $isFinal==true){
            //<cfdi:Impuestos  TotalImpuestosRetenidos="1033.33" TotalImpuestosTrasladados="800.00">
            if($param->totalImporteRet>0){
                $xml.=" TotalImpuestosRetenidos=\"".sprintf("%.2f",($param->importeRet+$param->otroImporteRet))."\"";
            }
            if($param->totalImporteTras>0){
                $xml.=" TotalImpuestosTrasladados=\"".sprintf("%.2f",($param->importeTras+$param->otroImporteTras))."\"";
            }
        }
        $xml.=">\n";
        $listImpuestos=$param->impuestos;
        $isTraslado=null;
        $isRetencion=null;
        $xmlTrasRet=null;
        $xmlTras=null;
        $listaTotales=array();
        if($concepto!=null){
            foreach ($listImpuestos as $impuestos) {
                $imp = explode("|", $impuestos);
                $idConceptoArr = $imp[0];
                if($concepto->id==((int)$idConceptoArr)){
                    $total=new \App\Pojo\Totales();
                    $idTipoImpuesto = $imp[2];
                    $idImpuesto = $imp[4];
                    $idImpFormat=str_pad((int) $idImpuesto,3,"0",STR_PAD_LEFT);
                    Log::info("idTipoImpuesto".$idTipoImpuesto);
                        if($idTipoImpuesto=='2' || $idTipoImpuesto=='4'){
                            $xmlTras.="<cfdi:Traslado ";
                            if($isFinal==null){
                                $xmlTras.="Base=\"".sprintf("%.2f",($concepto->importe - $concepto->descuento))."\" ";
                                
                            }
                            $importe=sprintf("%.2f",round($imp[7],2));
                            $xmlTras.="Importe=\"".$importe."\" ";
                            $iva=((float) $imp[6] / 100);
                            $ivaStr=str_pad($iva,8,"0");
                            $xmlTras.="Impuesto=\"".$idImpFormat."\" TasaOCuota=\"".$ivaStr."\" TipoFactor=\"Tasa\" />\n";                            
                            $total->valor=$importe;
                            $total->tipo=$idImpFormat;
                            $total->nombre="TRAS";
                            array_push($listaTotales,$total);
                            $isTraslado = true;
                        }else{
                            if($isFinal==null){
                                $xmlTrasRet.="<cfdi:Retencion ";
                                $xmlTrasRet.="Base=\"".sprintf("%.2f",($concepto->importe - $concepto->descuento))."\" ";
                                $xmlTrasRet.="Importe=\"".sprintf("%.2f",$this->truncate($imp[7],2))."\" ";
                                Log::info("imp[6]".$imp[6]);
                                $iva=((float) $imp[6] / 100);
                                $ivaStr=str_pad($iva,8,"0");
                                $xmlTrasRet.="Impuesto=\"".$idImpFormat."\" TasaOCuota=\"".$ivaStr."\" TipoFactor=\"Tasa\" />\n";                            
                            }else{
                                $xmlTrasRet.="<cfdi:Retencion ";
                                $importe=sprintf("%.2f",$this->truncate($imp[7],2));
                                $xmlTrasRet.="Importe=\"".$importe."\" ";
                                $xmlTrasRet.="Impuesto=\"".$idImpFormat."\" />\n";  
                                $total->valor=$importe;
                                $total->tipo=$idImpFormat;
                                $total->nombre="RET";
                                array_push($listaTotales,$total);
                            }
                            $isRetencion=true;
                        }

                    }
                }
            if($isFinal==null) {
                if($isTraslado!=null){
                    $xml.="<cfdi:Traslados>\n";
                    $xml.=$xmlTras;
                    $xml.="</cfdi:Traslados>\n";
                }
                if($isRetencion!=null){
                    $xml.="<cfdi:Retenciones>\n";
                    $xml.=$xmlTrasRet;
                    $xml.="</cfdi:Retenciones>\n";
                } 

            }else{
                if($isRetencion!=null){
                    $xml.="<cfdi:Retenciones>\n";
                    $xml.=$xmlTrasRet;
                    $xml.="</cfdi:Retenciones>\n";
                } 
                if($isTraslado!=null){
                    $xml.="<cfdi:Traslados>\n";
                    $xml.=$xmlTras;
                    $xml.="</cfdi:Traslados>\n";
                }
            }   
            
                
        }else{
                $xml.="<cfdi:".($param->totalImporteRet>0?"Retenciones":"Traslados")." >\n";
                $xml.="<cfdi:".($param->totalImporteRet>0?"Retencion":"Traslado")." ";
                $xml.="Importe=\"".($param->totalImporteRet>0?sprintf("%.2f",$this->truncate($param->totalImporteRet,2)):sprintf("%.2f",$this->truncate($param->totalImporteTras,2)))."\"  ";
                $imp=$listImpuestos[0];
                $impArr = explode("|", $imp);
                $iva=((int) $param->porcTasaImp / 100);
                $ivaStr=str_pad($iva,8,"0");
                $xml.="Impuesto=\"".str_pad((int) $impArr[2],3,"0",STR_PAD_LEFT)."\" TasaOCuota=\"".$ivaStr."\" TipoFactor=\"Tasa\" />\n";
                $xml.="</cfdi:".($param->totalImporteRet>0?"Retenciones":"Traslados")." >\n";
        }
        $xml.="</cfdi:Impuestos>\n";
        $param->totales=$listaTotales;
        return $xml;
    }
    
    
    /*
    private function getImpuestoConcepto($isFactura=true,$isFinal=false,\App\Pojo\DocumentoEmision $param){
        $xml="<cfdi:Impuestos ";
        $imp=null;
        $xmlTraslado=null;
        $xmlRetencion=null;
        if($isFinal){
            if($param->totalImporteRet>0){
                $xml.=" TotalImpuestosRetenidos=\"".sprintf("%.2f",($param->importeRet+$param->otroImporteRet))."\"";
            }
            if($param->totalImporteTras>0){
                $xml.=" TotalImpuestosTrasladados=\"".sprintf("%.2f",($param->importeTras+$param->otroImporteTras))."\"";
            }
        }
        $xml.=">\n";
            $listConceptos=$param->conceptos;
            $listImpuestos=$param->impuestos;
            foreach ($listConceptos as $concepto) {
                if($isFactura){
                    $imp = explode(",", $listImpuestos[0]);
                }
                $idTipoImpuesto = $imp[2];
                if($idTipoImpuesto=='2' || $idTipoImpuesto=='4'){
                    $xmlTraslado.="<cfdi:Traslado ";
                    if($isFinal==false){
                        $xmlTraslado.="Base=\"".sprintf("%.2f",$concepto->importe)."\" ";
                        //$xmlTraslado.="Base=\"".sprintf("%.2f",$base)."\" ";
                    }
                    $xmlTraslado.="Importe=\"".sprintf("%.2f",$this->truncate($imp[7],2))."\" ";
                    $iva=((int) $imp[6] / 100);
                    $ivaStr=str_pad($iva,8,"0");
                    $xmlTraslado.="Impuesto=\"".str_pad((int) $idTipoImpuesto,3,"0",STR_PAD_LEFT)."\" TasaOCuota=\"".$ivaStr."\" TipoFactor=\"Tasa\" />\n";
                }
                if($idTipoImpuesto=='1' || $idTipoImpuesto=='3'){
                    $xmlRetencion.="<cfdi:Retencion ";
                    if($isFinal==false){
                        $xmlRetencion.="Base=\"".sprintf("%.2f",$concepto->importe)."\" ";
                    }
                    $xmlRetencion.="Importe=\"".sprintf("%.2f",$this->truncate($imp[7],2))."\" ";
                    $iva=((int) $imp[6] / 100);
                    $ivaStr=str_pad($iva,8,"0",STR_PAD_RIGHT);
                    $xmlRetencion.="Impuesto=\"".str_pad((int) $idTipoImpuesto,3,"0",STR_PAD_LEFT)."\" TasaOCuota=\"".$ivaStr."\" TipoFactor=\"Tasa\" />\n";
                }
            }
            if($xmlTraslado!=null){
                $xml.="<cfdi:Traslados>\n";
                $xml.=$xmlTraslado;
                $xml.="</cfdi:Traslados>\n";
            }
            if($xmlRetencion!=null){
                $xml.="<cfdi:Retenciones>\n";
                $xml.=$xmlRetencion;
                $xml.="</cfdi:Retenciones>\n";
            }
        $xml.="</cfdi:Impuestos>\n";
        return $xml;
    }*/
    
    private function getImpuestos(\App\Pojo\DocumentoEmision $param) {
        $xml="";
        if($param->importeRet>0 || $param->otroImporteRet>0 ||$param->otroImporteTras>0 ||$param->importeTras>0){
            $xml.="<cfdi:Impuestos totalImpuestosRetenidos=\"".($param->importeRet+$param->otroImporteRet)."\" totalImpuestosTrasladados=\"".($param->importeTras+$param->otroImporteTras)."\">\n";
            $listImpuestos=$param->impuestos;
            $xmlTraslado=null;
            $xmlRetencion=null;
            foreach ($listImpuestos as $impuestos) {
                $imp = explode("|", $impuestos);
                $idTipoImpuesto = $imp[2];
                if($idTipoImpuesto=='2' || $idTipoImpuesto=='4'){
                    $xmlTraslado.="<cfdi:Traslado impuesto=\"".$imp[5]."\" tasa=\"".$imp[6]."\" importe=\"".$this->truncate($imp[7],2)."\" />\n";
                }
                if($idTipoImpuesto=='1' || $idTipoImpuesto=='3'){
                    $xmlRetencion.="<cfdi:Retencion impuesto=\"".$imp[5]."\" importe=\"".$this->truncate($imp[7],2)."\" />\n";
                }
            }
            if($xmlTraslado!=null){
                $xml.="<cfdi:Traslados>\n";
                $xml.=$xmlTraslado;
                $xml.="</cfdi:Traslados>\n";
            }
            if($xmlRetencion!=null){
                $xml.="<cfdi:Retenciones>\n";
                $xml.=$xmlRetencion;
                $xml.="</cfdi:Retenciones>\n";
            }
            $xml.="</cfdi:Impuestos>\n";
        }
        return $xml;
    }    
    public function getListaConcepto(\App\Pojo\DocumentoEmision $param) {
        $listConceptos=$param->conceptos;
        $listaCooncepto=array();
        foreach ($listConceptos as $concepto) {
            $con = explode("|", $concepto);
            $concepto=new \App\Pojo\Concepto();
            $concepto->id=($con[0]!=""?$con[0]:null);
            $concepto->codigo=($con[1]!=""?$con[1]:null);
            $concepto->nombre=($con[2]!=""?htmlspecialchars($con[2]):null);
            $concepto->idSatProductoServicio=($con[3]!=""?$con[3]:null);
            $concepto->identificacion=($con[4]!=""?$con[4]:null);
            $concepto->cantidad=($con[5]!=""?$con[5]:null);
            $concepto->idUnidad=($con[6]!=""?$con[6]:null);
            $concepto->unidadNom=($con[7]!=""?$con[7]:null);
            $concepto->predial=($con[8]!=""?$con[8]:null);  
            $concepto->precioUnitario=($con[9]!=""?$this->truncate($con[9],2):null);
            $concepto->importe=($con[10]!=""?$this->truncate($con[10],2):null);
            $concepto->descuento=($con[11]!=""?$this->truncate($con[11],2):null);
            array_push($listaCooncepto,$concepto);            
        }
        return $listaCooncepto;
    }
    
    public function getListaComplementoPago(\App\Pojo\DocumentoEmision $param) {
        $listPagos=$param->pagosList;
        $listaPagos=array();
        $formaPago=new \App\FormaPago();
        foreach ($listPagos as $pagoLine) {
            $com = explode("|", $pagoLine);
            $pago=new \App\Pojo\Pago();
            $pago->id=($com[0]!=""?$com[0]:null);
            $pago->idDocumento=($com[1]!=""?$com[1]:null);
            $pago->monedaDR=($com[2]!=""?htmlspecialchars($com[2]):null);
            $pago->metodoDePagoDR=($com[3]!=""?$com[3]:null);
            $pago->numParcialidad=($com[4]!=""?$com[4]:null);
            $pago->impSaldoAnt=($com[5]!=""?$this->truncate($com[5],2):null);
            $pago->impPagado=($com[6]!=""?$this->truncate($com[6],2):null);
            $pago->impSaldoInsoluto=($com[7]!=""?$this->truncate($com[7],2):null);
            $pago->formaDePagoP=($com[8]!=""?$com[8]:null);
            
            $pago->formaDePagoPDesc = $formaPago->getNombreById($pago->formaDePagoP);
            $pago->monedaP=($com[2]!=""?htmlspecialchars($com[2]):null);
            if($com[9]!=""){
                $fecha= explode(' ',$com[9]);
                $strFecha = $fecha[0]." 12:00:00";
                $pago->fechaPago=($strFecha);
            }else{
                $pago->fechaPago=null;
            }
            $pago->monto=($com[6]!=""?$this->truncate($com[6],2):null);
            $pago->serie=$param->serie;
            $pago->folio=$param->numero;
            $pago->rFCEmisorCtaOrigen = ($com[10]!=""?$com[10]:null);
            $pago->nombreBanco = ($com[11]!=""?$com[11]:null);
            $pago->ctaOrigen = ($com[12]!=""?$com[12]:null);
            $pago->rFCEmisorCtaBeneficiaria = ($com[13]!=""?$com[13]:null);
            $pago->ctaBeneficiaria = ($com[14]!=""?$com[14]:null);
            array_push($listaPagos,$pago);            
        }
        return $listaPagos;
    }
    
}
