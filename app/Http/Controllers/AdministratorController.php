<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use Illuminate\Support\Facades\Session;
use Hash;
use App\Pais;
use App\Estado;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Guard;
use \Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AdministratorController extends Controller {
    //

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
            $this->auth = $auth;
             $this->middleware('auth', ['except' => ['getRegimenFiscalJson']]);
            //$this->middleware('guest', ['only' => ['getRegimenFiscalJson']]);
    }
    /*public function __construct() {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexDatosContribuyente() {
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $usuPadre = $usuarioContribuyente->getUsuarioByContribuyente($contEmisor->id_contribuyente);
        $contEmisor->tipo_regimen = $contEmisor->tipo_persona;
        $contEmisor->regimen_fiscal = $contEmisor->id_regimen_fiscal;
        $isEmisor = $usuarioContribuyente->isContribuyentePadre($idUser);
        Log::error('**CER '.$contEmisor->id_archivo_cer." ..KEY ".$contEmisor->id_archivo_key);
        if($contEmisor->id_archivo_cer!=null){
            $arcCer=(new \App\Archivo())->getById($contEmisor->id_archivo_cer);
            $contEmisor->archivo_cer = $arcCer->nombre.".".$arcCer->extension;
        }else{
            $contEmisor->archivo_cer = "";
        }
        if($contEmisor->id_archivo_key!=null){
            $arcKey=(new \App\Archivo())->getById($contEmisor->id_archivo_key);
            $contEmisor->archivo_key = $arcKey->nombre.".".$arcKey->extension;
        }else{
            $contEmisor->archivo_key="";
        }
        
        
        $contEmisor->idPais = (new Pais())->getIdByNombre($contEmisor->pais);
        $paises = (new Pais())->getComboByPais();
        $estados = (new Estado())->getComboByPais(1);
        $mensaje = "Solo " . $usuPadre->name . " " . $usuPadre->email . " puede actualizar los datos";
        $disable = ($isEmisor ? false : true);
        return view('administracion/datosContribuyente/main')
                        ->with('listPais', $paises)
                        ->with('listEstado', $estados)
                        ->with('mensaje', $mensaje)
                        ->with('disable', $disable)
                        ->with('contribuyente', $contEmisor);
    }

    public function getRegimenFiscalJson(\Illuminate\Http\Request $request) {
        $tipoRegimen = $request->get('tipo_regimen');
        $tipoReg = ($tipoRegimen == "F" ? 1 : 2);
        $lista = (new \App\RegimenFiscal())->combo($tipoReg);
        return \Response::json($lista);
    }

    public function actualizaContribuyente(\Illuminate\Http\Request $request) {
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $contEmisor->razon_social = $request->get('razon_social');
        $contEmisor->tipo_regimen = $request->get('tipo_regimen');
        $idRegFiscal = $request->get('regimen_fiscal');
        $contEmisor->id_regimen_fiscal = $idRegFiscal;
        $contEmisor->regimen_fiscal = (new \App\RegimenFiscal())->getDescripcionById($idRegFiscal);
        $contEmisor->calle = $request->get('calle');
        $contEmisor->num_ext = $request->get('num_ext');
        $contEmisor->num_int = $request->get('num_int');
        $contEmisor->localidad = $request->get('localidad');
        $contEmisor->idPais = $request->get('pais');

        $contEmisor->estado = $request->get('estado');
        $contEmisor->municipio = $request->get('municipio');
        $contEmisor->colonia = $request->get('colonia');
        $contEmisor->codigo_postal = $request->get('codigo_postal');
        $contEmisor->correo_electronico = $request->get('correo_electronico');
        $contEmisor->celular = $request->get('cel');
        $contUpt = (new \App\Contribuyente())->actualizaByRFC($contEmisor);
        $id_archivo_cer = $request->get('id_archivo_cer');
        $id_archivo_key = $request->get('id_archivo_key');
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $rutaCer=$storagePath.$request->get('rfc').".cer";
        $rutaKey=$storagePath.$request->get('rfc').".key";
        Log::error('**ruta temporal '.$storagePath);
        Log::error('**RFC '.$request->get('rfc').".cer");
        Input::file('archivo_cer')->move($storagePath, $request->get('rfc').".cer");
        Input::file('archivo_key')->move($storagePath, $request->get('rfc').".key");
        $contentCer = File::get($rutaCer);
        $contentKey = File::get($rutaKey);
        $this->actualizaRegistraEmisor($rutaCer,$rutaKey,$request->get('sello_contrasenia'),$request->get('rfc'),$contEmisor);
        $archivo=new \App\Archivo();
        Log::error('**$id_archivo_key '.$id_archivo_key);
        Log::error('**$id_archivo_cer '.$id_archivo_cer);
        if($id_archivo_key==null || $id_archivo_key=='' || $id_archivo_cer==null || $id_archivo_cer==''){
            $arcKey=$archivo->guardar($contentCer,$request->get('rfc'),"key");
            $arcCer=$archivo->guardar($contentKey,$request->get('rfc'),"cer");
        }else{
            $archivo->actualizarPorId($id_archivo_key, $contentKey, $request->get('rfc'), "key");
            $archivo->actualizarPorId($id_archivo_cer, $contentCer, $request->get('rfc'), "cer");
            $arcKey=$archivo->getById($id_archivo_key);
            $arcCer=$archivo->getById($id_archivo_cer);
            /*$arcKey=$archivo->getById($id_archivo_key);
            $arcKey->cuerpo=$contentKey;
            $arcKey->nombre=$request->get('rfc');
            $arcKey->extension="key";
            $arcKey->save();
            $arcCer=$archivo->guardar($id_archivo_cer);
            $arcCer->cuerpo=$contentCer;
            $arcCer->nombre=$request->get('rfc');
            $arcCer->extension="cer";
            $arcCer->save();
             
            */
        }
        $contEmisor->contrasenia_sello=$request->get('sello_contrasenia');
        $contEmisor->id_archivo_cer=$arcCer->id;
        $contEmisor->id_archivo_key=$arcKey->id;
        $usuarioContribuyente->actualizarArchivosPasswd($contEmisor->id_users, 
                                    $contEmisor->id_contribuyente, 
                                    $contEmisor->id_archivo_cer, 
                                    $contEmisor->id_archivo_key, 
                                    $contEmisor->contrasenia_sello);
        $paises = (new Pais())->getComboByPais();
        $estados = (new Estado())->getComboByPais(1);
        if ($contUpt != null) {
            $mensaje = "Se actualizo correctamente Contribuyente";
        } else {
            $mensaje = "Ocurrio un error al actualizar contribuyente";
        }
        $disable = false;
        Storage::disk('local')->delete($request->get('rfc').".cer");
        Storage::disk('local')->delete($request->get('rfc').".key");
        return view('administracion/datosContribuyente/main')
                        ->with('listPais', $paises)
                        ->with('listEstado', $estados)
                        ->with('mensaje', $mensaje)
                        ->with('disable', $disable)
                        ->with('contribuyente', $contEmisor);
    }

    private function actualizaRegistraEmisor($rutaCer,$rutaKey,$selloContrasenia,$rfc,$contEmisor){
        $wSClient=new \App\Servicio\WebServiceClientService();
        $registrado=$wSClient->registrarEmisor($rutaCer, $rutaKey,$selloContrasenia,$rfc);
        $paises = (new Pais())->getComboByPais();
        $estados = (new Estado())->getComboByPais(1);
        $disable = false;
        if($registrado->errorCode!=0){
             Storage::disk('local')->delete($rfc.".cer");
             Storage::disk('local')->delete($rfc.".key");
             return view('administracion/datosContribuyente/main')
                        ->with('listPais', $paises)
                        ->with('listEstado', $estados)
                        ->with('mensaje', $registrado->errorMsg)
                        ->with('disable', $disable)
                        ->with('contribuyente', $contEmisor);
        }
    }
    
    public function indexAcutalizaPasswordUser() {
        $user = Auth::user();
        return view('administracion/cambiarPassword/main')
                        ->with('user', $user);
    }

    public function validadorCambioPassword(array $data) {
        $messages = [
            'old_password.required' => 'Please enter current password',
            'password.required' => 'Please enter password',
        ];
        $validator = Validator::make($data, [
                    'old_password' => 'required',
                    'password' => 'required|min:8',
                    'verify_password' => 'required|same:password',
                        ], $messages);
        return $validator;
    }

    public function postCambioPassword(\Illuminate\Http\Request $request) {
            $request_data = $request->All();
            $validator = $this->validadorCambioPassword($request_data);
            if ($validator->fails()) {
                return redirect('/administracion/cambiarPassword')
                        ->withErrors($validator)
                            ->withInput();
            } else {
                $current_password = Auth::User()->password;
                $oldPassword=$request->get('old_password');
                if (Hash::check($oldPassword, $current_password)) {
                    $user_id = Auth::User()->id;
                    $obj_user = \App\User::find($user_id);
                    $password=$request->get('password');
                    $obj_user->password = Hash::make($password);
                    $obj_user->save();
                    $user = Auth::user();
                    return view('administracion/cambiarPassword/main')
                        ->with('user', $user)
                        ->with('mensaje',"Se actualizo correctamente");          
                }else{
                    return redirect('/administracion/cambiarPassword')
                        ->withErrors("Escribir contraseña actual correctamente")
                            ->withInput();
                }
            }
    }
    
    public function indexUsers() {
        return view('administracion/usuarios/main');
    }
    
    public function getListaUsuariosJson(\Illuminate\Http\Request $request){
        $page = $request->get('page');
        $limit = $request->get('rows');
        //columna order
        $sidx = $request->get('sidx');
        $sord = $request->get('sord');
        if(!$sidx){
            $sidx =1;
        }
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $count=$usuarioContribuyente->getTotalUsuarioslistaGrid($contEmisor->id);
        if( $count >0 ) {
	$total_pages = ceil($count/$limit);
        } else {
                $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page=$total_pages;
        }
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        $responce = new \stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $usuarios=$usuarioContribuyente->getUsuariosListaGrid($sidx,$sord,$start,$limit,$contEmisor->id);
        $i=0;
        foreach ($usuarios as $usu) {
            $responce->rows[$i]['id']=$usu->id;
            $responce->rows[$i]['cell']=array($usu->id,$usu->name,$usu->email,
                                            $usu->created_at);
            $i++;
        }
        return \Response::json($responce);
    }

}
