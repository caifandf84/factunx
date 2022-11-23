<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Crypt;
use App\TipoDocumento;
use App\FormaPago;
use App\MetodoPago;
use App\Moneda;
use Illuminate\Support\Facades\Log;
use Auth;
class DocumentController extends Controller
{
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('documentos/main');
    }  
    
    public function type(Request $request,$new,$id) {
        $dId = Crypt::decrypt($id);
        Log::error('**$new'.$new);
        $usuCont=new \App\UsuarioContribuyente();
        if($dId==1){
            $idUser=Auth::user()->id;
            $usuarioContribuyente=new \App\UsuarioContribuyente();
            $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
            $timbres=$usuCont->getTimbres($contEmisor->id);
            if($timbres->timbres_restantes<=0){
                return view('configuracion/producto/aviso_comprar')
                    ->with('timbreDosponible',$timbres->timbres_contradados);
                
            }
            $tipoDocs = (new TipoDocumento())->get();
            $metodoPagos = (new MetodoPago())->combo();
            $formaPagos = (new FormaPago())->combo();
            $monedas = (new Moneda())->combo();
            $tipoComprobante=(new \App\TipoComprobante())->combo();
            $doc = $request->session()->get('doc');
            if($doc==null || $new==1){
                $docNew=new \App\Pojo\DocumentoEmision();
                $docNew->paso=1;
                $request->session()->put('doc', $docNew);
                $doc=$docNew;
            }
            return view('documentos/emision/main')
                    ->with('doc',$doc)
                    ->with('tipoDocs',$tipoDocs)
                    ->with('listMetodoPago',$metodoPagos)
                    ->with('listFormaPago',$formaPagos)
                    ->with('listTipoComprobante',$tipoComprobante)
                    ->with('listMoneda',$monedas);
        }else{
            return view('documentos/recepcion/main');
        }
    } 
    
    public function verListaEmitidos()
    {
        return view('documentos/listadoEmision/main');
    }  
    
    public function verParaCancelar()
    {
        return view('documentos/cancelar/main');
    }
    
    public function verListaTmpEmitidos()
    {
        return view('documentos/tmpEmision/main');
    } 
    
    public function  testAsignacion(){
        return view('auth.register_contribuyente');
    }
    
}
