<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ConceptoController extends Controller
{
    //
    private $mappingHeaderColumns = array(
                                            'bCodigo' => 'c_concepto.id',
                                            'bNombre' => 'c_concepto.nombre',
                                            'bUnidad' => 'c_unidad_prod.nombre',
                                            'bPrecioUnitario' => 'c_concepto.precio_unitario',
                                            'bNoIdentificacion' => 'c_concepto.identificacion',
                                            'bPredial' => 'c_concepto.predial',
                                            'bProdcutoServicio' => 'c_concepto.id_producto_servicio'
                                            );
    
    public function getAutocompleteCodigoJson(Request $request){
        //term=asdasd
        $codigo = $request->get('term');
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $concepto=new \App\Concepto();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $lista=$concepto->getListaByCodigo($codigo, $contEmisor->id);
        $results = array();
        foreach ($lista as $query)
	{
	    $results[] = [ 'id' => $query->id, 'value' => $query->id.' '.$query->nombre ];
	}
        return \Response::json($results);
    }
    
    public function getByIdJson(Request $request){
        //term=asdasd
        $id = $request->get('id');
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $concepto=new \App\Concepto();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $conceptoResult=$concepto->getByIdyContribuyente($id, $contEmisor->id);
        
        return \Response::json($conceptoResult);
    }

    public function getByCodigoBarrasJson(Request $request){
        //term=asdasd
        $codigoBarras = $request->get('codigoBarras');
        
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $concepto=new \App\Concepto();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $conceptoResult=$concepto->getByCodigoBarrasyContribuyente($codigoBarras, $contEmisor->id);
        //dd($contEmisor->id);
        return \Response::json($conceptoResult);
    }    
    
    public function getListaConceptosJson(Request $request){
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
        $concepto=new \App\Concepto();
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $count=$concepto->getTotallistaGrid($filterQuery,$contEmisor->id);
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
        $listConcento=$concepto->getListaGrid($sidx,$sord,$start,$limit,$filterQuery,$contEmisor->id);
        $i=0;
        foreach ($listConcento as $concepto) {
                         $sel = "<input style='height:22px;width:70px;' type='button' value='Seleccion' onclick=\"seleccConcepto('".$concepto->id."');\"  />"; 
            $responce->rows[$i]['id']=$concepto->id;
            $responce->rows[$i]['cell']=array($sel,$concepto->id,$concepto->nombre,$concepto->id_unidad,
                                              $concepto->unidad_nom,$concepto->id_producto_servicio,$concepto->precio_unitario,
                                              $concepto->identificacion,$concepto->predial);  
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
