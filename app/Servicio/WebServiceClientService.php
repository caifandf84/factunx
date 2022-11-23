<?php

namespace App\Servicio;

use Illuminate\Support\Facades\Log;
use SoapClient;
use Illuminate\Support\Facades\Storage;

/**
 * Description of WebServiceClientService
 *
 * @author Armando Córdova
 */
class WebServiceClientService {

    /**
     * Envia Factura a proveedor SAT
     * @param type $param
     */
    public function sendTimbradoDocumento($xmlSinTimbrar) {
        $credencial = $this->obtenerCredenciales();
        $base64Comprobante = base64_encode($xmlSinTimbrar);
        $intetos=1;
        $responce = new \stdClass();
        $responce->errorCode = 0;
        $random = rand(5, 999999999999);
        Log::error("IdComprobante WS TimbraCFDI [" . $random . "]");
        $environment = \App::environment();
        $isProd=false;
        if ($environment == 'Production') {
            $isProd=true;
        }
        for($x = 0; $x <= $intetos; $x++){
            
                $result = $this->timbrarByPass($credencial->user, $base64Comprobante, $random,$isProd);
                if($result!=null){
                    break;
                }
            }
            if ($result==null) {
                    $responce->errorCode = -1;
                    $responce->errorMsg = "Error al recibir servicio CURL bypass";
                    return $responce;
            }
            $resultJson = json_decode($result);
            $responce->idComprobante = $random;
            $responce->tipoExcepcion = $resultJson->respuesta0;
            $responce->numeroExcepcion = $resultJson->respuesta1;
            $responce->descripcionResultado = $resultJson->respuesta2;
            $responce->errorMsg = $resultJson->respuesta2;
            $responce->xmlTimbrado = ($resultJson->respuesta3!=''?base64_decode($resultJson->respuesta3):$resultJson->respuesta3);
            $responce->codigoQr = ($resultJson->respuesta4!=''?base64_decode($resultJson->respuesta4):$resultJson->respuesta4);
            $responce->cadenaOriginal = ($resultJson->respuesta5!=''?base64_decode($resultJson->respuesta5):$resultJson->respuesta5);
            if ($responce->xmlTimbrado == '') {
                $responce->errorCode = -1;
            }
        return $responce;
    }

    public function timbrarByPass($usuario, $xml, $idComprobante,$prod=false) {
        $result = null;
        try{
            $data = array("usuario" => $usuario, "xml" => $xml, "idComprobante" => $idComprobante);
            $data_string = json_encode($data);
            if($prod){                
                $ch = curl_init('http://74.208.176.185:80/convertir/emitirProd');
            }else{
                $ch = curl_init('http://74.208.176.185:80/convertir/emitirDev');
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)));
            $result = curl_exec($ch);
            if ($result === false) {
                $ret = curl_error($ch);
                Log::error("Error al ejecutar byPass [" . $ret . "]");
            }
        } catch (Exception $e){
            return $result;
        }
        return $result;
    }

    public function cancelarByPass($usuario, $folioUUID, $rfcEmisor,$prod=false) {
        $result = null;
        try{
            $data = array("usuario" => $usuario, "folioUUID" => $folioUUID, "rfcEmisor" => $rfcEmisor);
            $data_string = json_encode($data);
            Log::error("data_string:[".$data_string."]");
            if($prod){
                Log::error("bypass:[http://74.208.176.185:80/convertir/cancelarProd]");
                $ch = curl_init('http://74.208.176.185:80/convertir/cancelarProd');
            }else{
                Log::error("bypass:[http://74.208.176.185:80/convertir/cancelarDev]");
                $ch = curl_init('http://74.208.176.185:80/convertir/cancelarDev');
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)));
            $result = curl_exec($ch);
        } catch (Exception $e){
            return $result;
        }
        return $result;
    }    

    public function registraEmisorByPass($rfcEmisor,$base64Cer,$base64Key,$passwd,$usuario,$prod=false) {
        $result = null;
        try{
            $data = array("rfcEmisor" => $rfcEmisor, "base64Cer" => $base64Cer, "base64Key" => $base64Key, "passwd" => $passwd,"usuario"=>$usuario);
            $data_string = json_encode($data);
            if($prod){
                $ch = curl_init('http://74.208.176.185:80/convertir/registrarEmisorProd');
            }else{
                $ch = curl_init('http://74.208.176.185:80/convertir/registrarEmisorDev');
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)));
            $result = curl_exec($ch);
            //dd($result);
        } catch (Exception $e){
            return $result;
        }
        return $result;
    } 
    
    public function cancelarDocumento($rfcEmisor, $uuid) {
        $credencial = $this->obtenerCredenciales();
        $responce = new \stdClass();
        $responce->errorCode = 0;
        $intetos=1;
        $environment = \App::environment();
        $isProd=false;
        if ($environment == 'Production') {
            $isProd=true;
        }
        Log::error("Parametros cancelacion isProd [" . $isProd . "]rfcEmisor [".$rfcEmisor."]uuid[".$uuid."]");
        for($x = 0; $x <= $intetos; $x++){
                $result = $this->cancelarByPass ($credencial->user,$uuid,$rfcEmisor,$isProd);
                if($result!=null){
                    break;
                }
            }
            if ($result==null) {
                    $responce->errorCode = -1;
                    $responce->errorMsg = "Error al recibir servicio CURL bypass";
                    return $responce;
            }
            $resultJson = json_decode($result);
            $responce->tipoExcepcion = $resultJson->respuesta0;
            $responce->numeroExcepcion = $resultJson->respuesta1;
            $responce->descripcionResultado = $resultJson->respuesta2;
            $responce->errorMsg = $resultJson->respuesta2;
            $responce->xmlTimbrado = ($resultJson->respuesta3!=''?base64_decode($resultJson->respuesta3):$resultJson->respuesta3);
            $responce->codigoQr = ($resultJson->respuesta4!=''?base64_decode($resultJson->respuesta4):$resultJson->respuesta4);
            $responce->cadenaOriginal = ($resultJson->respuesta5!=''?base64_decode($resultJson->respuesta5):$resultJson->respuesta5);
            if ($responce->xmlTimbrado == '') {
                $responce->errorCode = -1;
            }
        return $responce;
    }

    public function registrarEmisor($rutaCer, $rutaKey, $passwd, $rfcEmisor) {
        $credencial = $this->obtenerCredenciales();
        $responce = new \stdClass();
        $responce->errorCode = 0;
        $intetos=1;
        $environment = \App::environment();
        $base64Cer = file_get_contents($rutaCer);
        $base64Cer = base64_encode($base64Cer);
        $base64Key = file_get_contents($rutaKey);
        $base64Key = base64_encode($base64Key);
        
        $isProd=false;
        if ($environment == 'Production') {
            $isProd=true;
        }
        
        for($x = 0; $x <= $intetos; $x++){
                $result = $this->registraEmisorByPass($rfcEmisor, $base64Cer, $base64Key,$passwd,$credencial->user,$isProd);
                if($result!=null){
                    break;
                }
            }
            if ($result==null) {
                    $responce->errorCode = -1;
                    $responce->errorMsg = "Error al recibir servicio CURL bypass";
                    return $responce;
            }
            $resultJson = json_decode($result);
            //dd($resultJson);
            if($resultJson->respuesta1 != 0){
                $responce->tipoExcepcion = $resultJson->respuesta0;
                $responce->numeroExcepcion = $resultJson->respuesta6;
                $responce->descripcionResultado = $resultJson->respuesta7;
                $responce->errorMsg = $resultJson->respuesta7;
                $responce->xmlTimbrado = ($resultJson->respuesta3!=''?base64_decode($resultJson->respuesta3):$resultJson->respuesta3);
                $responce->codigoQr = ($resultJson->respuesta4!=''?base64_decode($resultJson->respuesta4):$resultJson->respuesta4);
                $responce->cadenaOriginal = ($resultJson->respuesta5!=''?base64_decode($resultJson->respuesta5):$resultJson->respuesta5);
            }
           
        return $responce;
    }

    private function obtenerCredenciales() {
        $environment = \App::environment();
        $confGral = new \App\ConfiguracionGral();
        $responce = new \stdClass();
        if ($environment != 'Production') {
            $confU = $confGral->getByNombre("usuario_ws_desa");
            $responce->user = $confU->valor;
            $confUrl = $confGral->getByNombre("url_ws_desa");
            $responce->url = $confUrl->valor;
        } else {
            $confU = $confGral->getByNombre("usuario_ws_prod");
            $responce->user = $confU->valor;
            $confUrl = $confGral->getByNombre("url_ws_prod");
            $responce->url = $confUrl->valor;
        }
        return $responce;
    }

}
