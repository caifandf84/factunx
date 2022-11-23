<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SerieNumeroController extends Controller
{
    //
    private $mappingHeaderColumns = array(
                                            'bCodigo' => 'id',
                                            'bSerie' => 'serie',
                                            'bNumero' => 'numero'
                                            );    
    
    
    public function getListaSerieNumero(){
        return view('documentos.emision.serie_numero.buscar');
    }
    public function getListaSerieNumeroJson(Request $request){
        $page = $request->get('page');
        $limit = $request->get('rows');
        //columna order
        $sidx = $request->get('sidx');
        $sord = $request->get('sord');
        if(!$sidx){
            $sidx =1;
        }
        $serieNumero=new \App\SerieNumero();
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $count=$serieNumero->getTotallistaGrid($contEmisor->id);
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
        $listSerieNumero=$serieNumero->getListaGrid($contEmisor->id, $sidx, $sord, $start, $limit);
        $i=0;
        foreach ($listSerieNumero as $serieNum) {
            $sel = "<input style='height:22px;width:70px;' type='button' value='Seleccion' onclick=\"seleccSerieNumero('".$serieNum->id."');\"  />"; 
            $edit = "<input style='height:22px;width:70px;' type='button' value='Editar' onclick=\"editaSerieNumero('".$serieNum->id."');\"  />"; 
            $elim = "<input style='height:22px;width:70px;' type='button' value='Eliminar' onclick=\"eliminaSerieNumero('".$serieNum->id."');\"  />"; 
            $responce->rows[$i]['id']=$serieNum->id;
            $responce->rows[$i]['cell']=array($sel,$edit,$elim,$serieNum->id,$serieNum->serie,$serieNum->numero);  
            $i++;
        }
        return \Response::json($responce);
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
