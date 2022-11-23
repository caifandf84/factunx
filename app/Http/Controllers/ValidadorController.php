<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Input;

/**
 * valida CFDI que fueron timbrados previamente
 */
class ValidadorController extends Controller
{
    
    private static $CODE=null;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }
    
    /**
     * Genera codigo de acceso
     * @return \Faker\Provider\Uuid
     */
    public function createAccess(\Illuminate\Http\Request $request){
        $user = $request->header('user');
        $passwd = $request->header('passwd');
        $code = uniqid('secret_');
        self::$CODE=$code; 
        return \Response::json(['keyAccess' => $code]); 
    }
    
    public function validaCFDITimbrado(Request $request) {
        $key = $request->header('keyAccess');
        //$data = json_decode($request->getContent(), true);
        $xml = $request->input('xml');
        if($key==null){
            return \Response::json(['errorCode' => '1','errorMsg'=>'Se requiere parametro keyAccess']);
        }else{
            $data=$this->byPassValidation($xml);
            $vars = explode('|', $data);
            //$jsonArray = json_decode($data);
            if($vars!=null && $vars!=''){
                return \Response::json(['errorCode' => '200','versionCFDI'=>substr($vars[2],0,3),
                    'EsValido'=>substr($vars[3],0,4),'EstadoSAT'=>substr($vars[24],0,7),'UUID'=> substr($vars[19],0,36)]);
            }else{
                return \Response::json(['errorCode' => '2','errorMensaje'=>'Sin informacion del SAT']);
                
            }
        }
    }
    
    private function byPassValidation($xml){
        try{
            $data = array("xml" => $xml);
            $data_string = json_encode($data);
            $ch = curl_init('https://jerry-built-circumf.000webhostapp.com/WebServicesValidador.php');
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)));
            return curl_exec($ch);
        } catch (Exception $e){
            return null;
        }
    }
    
}
