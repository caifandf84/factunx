@extends('layouts.app')

@section('content')

<div class="container">
 @include('administracion.datosContribuyente.formulario',[
                            'disable' => $disable,
                            'listPais'=>$listPais,
                            'listEstado'=>$listEstado,
                            'contribuyente' => $contribuyente
                        ])
</div>

@endsection
