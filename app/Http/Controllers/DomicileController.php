<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Session;
use App\Pais;
use App\Municipio;
use App\Colonia;
use DB;

class DomicileController extends Controller
{
    //
    
    public function getListPaisJson()
    {
        if(Session::token() != Request::header('X-CSRF-Token')){
            throw new Illuminate\Session\TokenMismatchException;
        } 
        $paises=(new Pais())->getComboByPais();
        return \Response::json($paises->toArray());
    }
    
    public function getComboMunicipioJson(\Illuminate\Http\Request $request){
        if(Session::token() != Request::header('X-CSRF-Token')){
            throw new Illuminate\Session\TokenMismatchException;
        }
        $idEstado = $request->get('id_estado');
        return \Response::json((new Municipio())->getComboByEstado($idEstado));
    }    
 
    public function getComboColoniaJson(\Illuminate\Http\Request $request){
        $idMunicipio = $request->get('id_municipio');
        return \Response::json((new Colonia())->getComboByMunicipio($idMunicipio));
    }
    
    public function getCodigoPostalJson(\Illuminate\Http\Request $request){
        if(Session::token() != Request::header('X-CSRF-Token')){
            throw new Illuminate\Session\TokenMismatchException;
        }
        $idColonia = $request->get('id_colonia');
        return \Response::json((new \App\CodigoPostal())->getByColonia($idColonia));
    }
    
    
    public function getDireccionPorCPJson(\Illuminate\Http\Request $request){
        if(Session::token() != Request::header('X-CSRF-Token')){
            throw new Illuminate\Session\TokenMismatchException;
        }
        $codigoPostal = $request->get('codigo_postal');
        return \Response::json($this->getAllDireccionPorCp($codigoPostal));
    }  
    
    private function getAllDireccionPorCp($codigoPostal){
        return DB::table('cat_codigo_postal')
            ->join('cat_colonia', 'cat_colonia.id', '=', 'cat_codigo_postal.id_colonia')
            ->join('cat_municipio_edo', 'cat_municipio_edo.id', '=', 'cat_colonia.id_municipio_delg')
            ->join('cat_estado', 'cat_estado.id', '=', 'cat_municipio_edo.id_estado') 
            ->join('cat_pais', 'cat_pais.id', '=', 'cat_estado.id_pais') 
            ->where('cat_codigo_postal.nombre', '=', $codigoPostal)    
            ->select('cat_pais.*', 'cat_estado.*', 'cat_estado.*','cat_municipio_edo.*','cat_colonia.*','cat_codigo_postal.*')
            ->first();
        
    }
    
}
