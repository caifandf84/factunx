@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Bienvenido</div>
                <div class="panel-body">
                    <p class="bg-info">
                        Bienvenido,<br/>
                        Ya puede ingresar al sistema FACTUNX, este sistema te proporciona las siguientes caracteristicas:<br/>
                        Facturar ante el SAT de manera Facil.<br/>
                        Cancelar Facturas.<br/>
                        Ver datos estadisticos de tus facturaciones.<br/>
                        Agregar tus productos de manera sencilla.<br/>
                        Autorizar todos los usuarios que requiera ingresar al sistema<br/><br/>
                        <strong>A disfrutar:</strong>
                    </p>
                    
                    <a class="btn btn-primary" href="{{url('/home')}}">Ingresar</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
