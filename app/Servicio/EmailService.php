<?php

/*
 * Esta clase envia correos electronicos
 */

namespace App\Servicio;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mail;
/**
 * Description of EmailService
 *
 * @author Armando
 */
class EmailService {
    //put your code here
    
    private $fromConuxi="factura@conuxi.com";
    
    public function sendDocumentoEmisionByQueue($idEmision){
        Log::error('Inicio sendByQueue');
        Log::error('$idEmision '.$idEmision);
        $mailVOEmision=$this->crearEmailEmision($idEmision);
        $mailVOReceptor=$this->crearEmailEmisionReceptor($idEmision);
        dispatch(new \App\Jobs\EmailJobService($mailVOEmision,"docEmision"));
        dispatch(new \App\Jobs\EmailJobService($mailVOReceptor,"docEmisionRec"));
        Log::error('Fin sendByQueue');
    }
    
    private function crearEmailEmision($idEmision){
        $emi=new \App\Emision();
        $objEmision=$emi->getById($idEmision);
        $doc=$this->getDocumentoEmision($objEmision);
        $mailVO = new \App\Pojo\MailVO();
        $mailVO->urlTemplate='email.doc_emision';
        $mailVO->parametro=['doc'=>$doc];
        $mailVO->to=$doc->email;
        $mailVO->from=$this->fromConuxi;
        $mailVO->subject='Comprobante emisión';
        return $mailVO;
    }

    public function sendPagoBienvenida($to,$refPdf,$producto,$isComprado){
        $mailVO = new \App\Pojo\MailVO();
        $mailVO->to=$to;
        $mailVO->parametro=['producto'=>$producto,'isComprado'=>$isComprado];
        if(!$isComprado){
            $mailVO->attachs[0]=$refPdf;
            $mailVO->subject='FactuNX Referencia de pago OXXO';
        }else{
            $mailVO->attachs=null;
            $mailVO->subject='FactuNX agradece tu compra';
        }
        Mail::send("email.oxxo_referencia", $mailVO->parametro, function ($message) use($mailVO) {
            $message->from('factura@conuxi.com');
            $message->to($mailVO->to)->subject($mailVO->subject);
            if($mailVO->attachs!=null){
                $message->attach($mailVO->attachs[0]);
            }
        });
    }
    
    public function sendEmisor($idEmisior,$email=null) {
        $mailVO=$this->crearEmailEmisionReceptor($idEmisior);
        Log::error('sendEmisorOnline ' . $mailVO->urlTemplate);
        Log::error('sendEmisor Correo To ' . $mailVO->to);
        if($email!=null){
            $mailVO->to=$email;
        }
        Log::error('sendEmisor Correo from ' . $mailVO->from);
        Mail::send($mailVO->urlTemplate, $mailVO->parametro, function ($message) use($mailVO) {
            $message->from('factura@conuxi.com');
            $message->to($mailVO->to)
                    ->subject($mailVO->subject);
            $size = sizeOf($mailVO->attachs);
            for ($i = 0; $i < $size; $i++) {
                $message->attach($mailVO->attachs[$i]);
            }
        });
        $size = sizeOf($mailVO->attachs);
        for ($i = 0; $i < $size; $i++) {
            unlink($mailVO->attachs[$i]);
        }
    }
    
    public function sendAsignacionConfirmacion($user,$emailCont) {
        Mail::send("email.confirmacion_asignacion", $user, function ($message) use($emailCont) {
            $message->from('factura@conuxi.com');
            $message->to($emailCont)
                    ->subject("Confirmación Asignación de Usuario FACTUNX");
        });
    }
    
    public function sendBienvenido($email) {
        Mail::send("email.bienvenido", [], function ($message) use($email) {
            $message->from('factura@conuxi.com');
            $message->to($email)
                    ->subject("Bienvenido al sistema FACTUNX");
        });
    }    
    
    private function crearEmailEmisionReceptor($idEmision){
        $emi=new \App\Emision();
        $objEmision=$emi->getById($idEmision);
        $doc=$this->getDocumentoReceptor($objEmision);
        
        Storage::disk('local')->put($objEmision->nombrePdf.'.'.$objEmision->extPdf, $objEmision->cuerpoPdf);
        Storage::disk('local')->put($objEmision->nombreXml.'.'.$objEmision->extXml, $objEmision->cuerpoXml);
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $storagePdf=$storagePath.$objEmision->nombrePdf.'.'.$objEmision->extPdf;
        $storageXml=$storagePath.$objEmision->nombreXml.'.'.$objEmision->extXml;
        $files=array($storagePdf,$storageXml);
        
        $mailVO = new \App\Pojo\MailVO();
        $mailVO->urlTemplate='email.doc_receptor';
        $mailVO->parametro=['doc'=>$doc];
        $mailVO->to=$doc->email;
        $mailVO->from=$this->fromConuxi;
        $mailVO->subject='Comprobante '.$doc->tipoDoc.' Fiscal SAT';
        $mailVO->attachs=$files;
        return $mailVO;
    }
    
    private function getDocumentoReceptor($emision){
        $doc=new \App\Pojo\DocumentoEmision();
        $doc->tipoDoc=$emision->nomTipoDocumento; 
        $doc->email=$emision->correoElectronicoReceptor;
        $doc->total=$emision->total;
        $doc->moneda=$emision->id_moneda;
        $doc->rfc=$emision->rfcReceptor;
        $doc->razonSocial=$emision->razonSocialReceptor;
        return $doc;
    }
    
    private function getDocumentoEmision($emision){
        $doc=new \App\Pojo\DocumentoEmision();
        $doc->razonSocial=$emision->razonSocialReceptor;
        $doc->rfc=$emision->rfcReceptor;
        $doc->total=$emision->monto;
        $doc->tipoDoc=$emision->nomTipoDocumento; 
        $doc->email=$emision->correoElectronicoEmision;
        return $doc;
    }
    
}
