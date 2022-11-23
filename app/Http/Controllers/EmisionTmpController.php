<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \App\Pojo\DocumentoEmision;
use Auth;

class EmisionTmpController extends Controller {

    //
    private $mappingHeaderColumns = array('fecha' => 'created_at');

    public function verLSeleccionTmpEmision(\Illuminate\Http\Request $request)
    {
        $idDocumento = $request->get('idDocumento');
        $emisionTmp = new \App\EmisionTmp();
        $objTmp=$emisionTmp->getById($idDocumento);
        $docJson=json_decode($objTmp->proceso_emision, true);
        $doc=DocumentoEmision::withData($docJson);
        $request->session()->put('doc', $doc);
        return redirect('documentos/emision/datosGeneral');
    }
    
    public function direccionarSeleccionTmpEmision()
    {
        return redirect('documentos/emision/datosGeneral');
    }
    
    public function getListaPorEmitirJson(Request $request) {
        $page = $request->get('page');
        $limit = $request->get('rows');
        //columna order
        $sidx = $request->get('sidx');
        $sord = $request->get('sord');
        if (!$sidx) {
            $sidx = 1;
        }
        $idUser = Auth::user()->id;
        $usuarioContribuyente = new \App\UsuarioContribuyente();
        $contEmisor = $usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $emisionTmp = new \App\EmisionTmp();
        $count = $emisionTmp->getTotalPorEmisor($contEmisor->id);
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
        $sidx = $this->getValueHeader($sidx);
        $emisiones = $emisionTmp->getListaGrid($contEmisor->id, $sidx, $sord, $start, $limit);
        $i = 0;
        foreach ($emisiones as $emision) {
            $seleccion = "<input style='height:22px;width:70px;' type='button' value='Seleccionar' onclick=\"seleccionaDocumento('" . $emision->id . "');\"  />";
            $responce->rows[$i]['id'] = $emision->id;
            $tipoDoc=new \App\TipoDocumento();
            $objTipoDoc=$tipoDoc->getById($emision->id_tipo_comprobante);
            $responce->rows[$i]['cell'] = array($seleccion,
                $emision->id, 
                $emision->id_tipo_comprobante,
                $objTipoDoc->nombre, 
                $emision->serie, 
                $emision->numero,
                $emision->created_at,
                $emision->total);
            $i++;
        }
        return \Response::json($responce);
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
