<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio\SeguridadService;
use Auth;
use \Crypt;

class HomeController extends Controller
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
    public function index(Request $request)
    {
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $contEmisorPadre=$usuarioContribuyente->getContribuyenteByUsuario($contEmisor->id_users);
        $emision = new \App\Emision();
        $anio=date("Y");
        $mes=date("m");
        //dd($request->session()->get('timbres'));
        $totalTimbrado=$emision->getTotalPorMesAnio($contEmisor->id,$mes,$anio);
        $menu=(new SeguridadService())->obtieneMenuPorIdRol(1);
        //dd($contEmisor);
        $request->session()->put('timbres',$contEmisorPadre->timbres_restantes);
        if (!$request->session()->exists('menus')) {
            $request->session()->put('menus', $menu);
        }
        return view('home')
                ->with('totalTimbrado',$totalTimbrado)
                ->with('anio',$anio)
                ->with('mes',$mes);
    }
}
