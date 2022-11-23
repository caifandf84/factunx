<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{

    public $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
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
   
    
    public function indexTimbrado(Request $request)
    {
        $anio = $request->get('anio');
        $mes = $request->get('mes');
        if($anio!=null && $mes!=null){
            return $this->indexTimbradConValores($anio,$mes);
        }else{
            $anio=date("Y");
            $mes=date("m");
            return $this->indexTimbradConValores($anio,$mes);
        }
    }    

    public function indexMesAnioTimbrado(Request $request,$mes,$anio)
    {
        return $this->indexTimbradConValores($anio,$mes);
    } 
    
    public function indexTimbradConValores($anio,$mes){
        $tTimbradoAnio=$this->getTotalTimbradoAnio($anio);
        $tCanceladoAnio=$this->getTotalCanceladoTimbradoAnio($anio);
        $timbrado=(new \App\Emision())->getTotalTimbradosPorMesAnio($this->getIdEmisor(), $mes, $anio);
        $cancelado=(new \App\Emision())->getTotalCanceladoPorMesAnio($this->getIdEmisor(), $mes, $anio);
        
        $anios=$this->getAnios($anio);
        $meses=$this->getMeses();
        return view('dashboard/timbrado/main')
                ->with('anios',$anios)
                ->with('meses',$meses)
                ->with('anio',$anio)
                ->with('mes',$mes)
                ->with('tCanceladoAnio',$tCanceladoAnio)
                ->with('mesTxt',$this->meses[($mes-1)])
                ->with('timbradoMes',$timbrado)
                ->with('canceladoMes',$cancelado)
                ->with('tTimbradoAnio',$tTimbradoAnio);
    }
    
    private function getIdEmisor(){
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        return $contEmisor->id;
    }
    
    private function getTotalTimbradoAnio($anio){
        $timbrado=array();
        for ($i = 1; $i <13; $i++) {
            $mes=str_pad($i,2,"0",STR_PAD_LEFT);
            $timbrado[($i-1)]=(new \App\Emision())->getTotalTimbradosPorMesAnio($this->getIdEmisor(), $mes, $anio);
        }
        return $timbrado;
    }
    
    private function getTotalCanceladoTimbradoAnio($anio){
        $timbrado=array();
        for ($i = 1; $i <13; $i++) {
            $mes=str_pad($i,2,"0",STR_PAD_LEFT);
            $timbrado[($i-1)]=(new \App\Emision())->getTotalCanceladoPorMesAnio($this->getIdEmisor(), $mes, $anio);
        }
        return $timbrado;
    }
    
    private function getAnios($anio){
        $anios=array();
        $rango=$anio-5;
        for ($i = $rango; $i <($anio+6); $i++) {
            $anios[$i]=$i;
        }
        return $anios;
    }

    private function getMeses(){
        $meses=array();
        for ($i = 1; $i <13; $i++) {
            $key=str_pad($i,2,"0",STR_PAD_LEFT);
            $meses[$key]=$this->meses[($i-1)];
        }
        return $meses;
    }
    
}
