<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use Validator;

class DocumentCancelacionController extends Controller
{
    //
    
        private $mappingHeaderColumns = array(
                                            'idTipoDocumento' => 'id_tipo_documento',
                                            'nomTipoDocumento' => 'nomTipoDocumento',
                                            'fecha' => 'fecha_emision',
                                            'rfcEmisor' => 'emisor.rfc',
                                            'nomEmisor' => 'emisor.razon_social',
                                            'rfcReceptor' => 'receptor.rfc',
                                            'nomReceptor' => 'receptor.razon_social',
                                            'moneda' => 'id_moneda',
                                            'monto' => 'monto',
                                            'pdf' => 'id_archivo_pdf',
                                            'xml' => 'id_archivo_xml');
    
        public function cancelarDocumento(Request $request){
            $idDocumento = $request->get('idDocumento');
            $emitido=new \App\Emision();
            $docEmitido=$emitido->getById($idDocumento);
            
            $wsClient=new \App\Servicio\WebServiceClientService();
            $responce=$wsClient->cancelarDocumento($docEmitido->rfcEmisor, $docEmitido->uuid);
            Log::error("responce->errorCod [" . $responce->numeroExcepcion . "]");
            if($responce->numeroExcepcion == '202' || $responce->numeroExcepcion == '0' ){
                $emitido->cancelarEmisorPorId($docEmitido->id);
                return view('documentos/cancelar/main')->with('mensaje',$responce->errorMsg);
            }else{
                //dummy para activar Errors
                $validator = Validator::make($request->all(), [
                    'person.*.email' => 'email|unique:users',
                    'person.*.first_name' => 'required_with:person.*.last_name',
                ]);
                $validator->errors()->add('Validador SAT', $responce->errorMsg);
                return redirect('/documentos/ver/cancelar')
                            ->withErrors($validator)
                            ->withInput();
            }
            //$emitido->cancelarEmisorPorId($docEmitido->id);
            //return view('documentos/cancelar/main')->with('mensaje',$responce->errorMsg);
        }
        
        public function getListaEmitidosJson(Request $request){
        $filters = $request->get('filters');
        $filterQuery=$this->getFilterGrid($filters);    
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
        $emision=new \App\Emision();
        $count=$emision->getTotallistaGrid($filterQuery,$contEmisor->id);
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
        $sidx=$this->getValueHeader($sidx);
        $filtro=$this->filtros($request);
        $emisiones=$emision->getListaGrid($contEmisor->id,$sidx,$sord,$start,$limit,$filtro);
        $i=0;
        foreach ($emisiones as $emision) {
            $cancel = "<input style='height:22px;width:70px;' type='button' value='Cancelar' onclick=\"cancelaDocumento('".$emision->id."');\"  />";
            $responce->rows[$i]['id']=$emision->id;
            $responce->rows[$i]['cell']=array($cancel,$emision->id,$emision->id_tipo_documento,$emision->nomTipoDocumento,
                                              $emision->serie,$emision->numero,
                                              $emision->fecha_emision,
                                              $emision->rfcEmisor,$emision->razonSocialEmisor,
                                              $emision->rfcReceptor,$emision->razonSocialReceptor,
                                              $emision->id_moneda,$emision->monto,
                                              $emision->estatus,$emision->uuid);
            $i++;
        }
        return \Response::json($responce);
    } 
    
    private function filtros(Request $request){
        $responce = new \stdClass();
        $responce->rfcCliente=$request->get('rfc_cliente');
        $responce->nombreCliente=$request->get('nombre_cliente');
        $responce->serie=$request->get('serie');
        $responce->folio=$request->get('folio');
        $responce->fechaDesde=$request->get('fecha_desde');
        $responce->fechaHasta=$request->get('fecha_hasta');
        return $responce;
    }
    
    private function getFilterGrid($filters){
        $respuestas=array();
        if($filters!=null){
            $array = json_decode($filters, true);
            $rules=$array["rules"];
            foreach ($rules as $value) {
                $obj = new \stdClass();
                $obj->field = $this->getValueHeader($value["field"]);
                $obj->data = $value["data"];
                if($obj->field!="accion"){
                    array_push($respuestas,$obj);
                }
            }
        }
        return $respuestas;
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
    
    
}
