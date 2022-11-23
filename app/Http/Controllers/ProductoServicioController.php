<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoServicioController extends Controller {

    private $mappingHeaderColumns = array(
        'bpsNombre'=>'nombre',
        'bpsid'=>'id',
        'bpsIdSat' => 'id_sat'
    );

    public function getListaProductoServicioJson(Request $request) {
        $filters = $request->get('filters');
        $page = $request->get('page');
        $limit = $request->get('rows');
        //columna order
        $sidx = $request->get('sidx');
        $sord = $request->get('sord');
        if (!$sidx) {
            $sidx = 1;
        }
        $filtro = $this->getFilterGrid($filters);
        $prodServ = new \App\ProductoServicio();
        $count = $prodServ->getTotallistaGrid($filtro);
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = $limit * $page - $limit; // do not put $limit*($page - 1)
        $responce = new \stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $sidx=$this->getValueHeader($sidx);

        $prodServs = $prodServ->getListaGrid($sidx, $sord, $start, $limit, $filtro);
        $i = 0;
        foreach ($prodServs as $pServ) {
            $sel = "<input style='height:22px;width:70px;' type='button' value='Seleccion' onclick=\"seleccProductoServicio('" . $pServ->id . "');\"  />";
            $responce->rows[$i]['id'] = $pServ->id;
            $responce->rows[$i]['cell'] = array($pServ->id, $sel, $pServ->id_sat, $pServ->nombre);
            $i++;
        }
        return \Response::json($responce);
    }

    private function getFilterGrid($filters) {
        $respuestas = array();
        //dd($filters);
        if ($filters != null) {
            $array = json_decode($filters, true);
            $rules = $array["rules"];
            foreach ($rules as $value) {
                $obj = new \stdClass();
                $obj->field = $this->getValueHeader($value["field"]);
                $obj->data = $value["data"];
                if ($obj->field != "accion") {
                    array_push($respuestas, $obj);
                }
            }
        }
        return $respuestas;
    }

    private function getValueHeader($val) {
        foreach ($this->mappingHeaderColumns as $key => $value) {
            if ($key == $val) {
                return $value;
            }
        }
        return $val;
    }

}
