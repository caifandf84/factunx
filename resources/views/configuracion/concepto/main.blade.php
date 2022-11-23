@extends('layouts.app')

@section('content')

<div class="container">
    @isset($mensaje)
    <div class="row">
         <ul class="list-group" >
            <li class="list-group-item list-group-item-success" >{{ $mensaje }}</li>
        </ul>
    </div> 
    @endisset
    <div class="alert alert-info" >
        <strong>Cargar Conceptos</strong><br/>Los conceptos son los productos para registrar.
        Una vez registrado se asigna un Identificador que funciona como busqueda rapida en la generacion
        de documentos como Facturas,Recibos,Etc.
    </div>
    <iframe height="620" align="middle" width="100%" border="0" src="https://docs.google.com/spreadsheets/d/{{$sheetName}}/edit?usp=sharing"></iframe>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-6">
                {{ Form::open(array('url' => 'configuracion/concepto/generaCodBarras','method' => 'get')) }}  
                <div class="pull-left">
                    {!! Form::submit('Generar Codigos de Barra',['class' => 'btn btn-info']) !!}
                </div> 
                {{ Form::close() }}
            </div>
            <div class="col-sm-6">
                {{ Form::open(array('url' => 'configuracion/concepto/guardar','method' => 'get')) }}  
                <div class="pull-right">
                    {!! Form::submit('Guardar',['class' => 'btn btn-info']) !!}
                </div>
                {{ Form::close() }}
            </div> 
        </div>
    </div>
</div>
@endsection