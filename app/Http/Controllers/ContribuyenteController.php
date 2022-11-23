<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ContribuyenteController extends Controller
{
    //
    
        private $mappingHeaderColumns = array(
                                            'razonSocial' => 'razon_social',
                                            'numExt' => 'num_ext',
                                            'correo' => 'correo_electronico',
                                            'bMunicipio' => 'municipio',
                                            'bEstado' => 'estado',
                                            'bPais' => 'pais',
                                            'bColonia' => 'colonia',
                                            'bRfc' => 'rfc'
                                            );
    
        public function getListaContribuyentesJson(Request $request){
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
        $contribuyente=new \App\Contribuyente();
        if(Auth::user()==null){
            $count=$contribuyente->getTotallistaGrid($filterQuery,null);
        }else{
            $idUser=Auth::user()->id;
            $usuarioContribuyente=new \App\UsuarioContribuyente();
            $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
            $count=$contribuyente->getTotallistaGrid($filterQuery,$contEmisor->id);
        }
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
        if(Auth::user()==null){
            $listCont=$contribuyente->getListaGrid($sidx,$sord,$start,$limit,$filterQuery,null);
        }else{
            $listCont=$contribuyente->getListaGrid($sidx,$sord,$start,$limit,$filterQuery,$contEmisor->id);
        }
        $i=0;
        foreach ($listCont as $cont) {
            $sel = "<input style='height:22px;width:70px;' type='button' value='Seleccion' onclick=\"seleccContribuyente('".$cont->id."');\"  />"; 
            $responce->rows[$i]['id']=$cont->id;
            $responce->rows[$i]['cell']=array($cont->id,$sel,$cont->rfc,$cont->razon_social,
                                              $cont->calle,$cont->num_ext,$cont->estado,
                                              $cont->municipio,$cont->colonia,$cont->correo_electronico,
                                              $cont->num_int,$cont->localidad,$cont->codigo_postal,
                                              $cont->id_colonia,$cont->id_municipio,$cont->id_estado,
                                              $cont->pais,$cont->id_pais,$cont->celular);  
            $i++;
        }
        return \Response::json($responce);
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
