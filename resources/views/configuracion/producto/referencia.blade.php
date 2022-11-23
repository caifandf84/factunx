@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <ul class="list-group" >
            @foreach($errors->all() as $error)
            <li class="list-group-item list-group-item-danger" >{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    {{ Form::open(array('url' => '/producto/comprar/pagar/medioDePago','method' => 'post','id'=>'formPagoTarjeta')) }} 
    <div class="panel panel-success">
        <div class="panel-heading">Pago en el OXXO</div>
        <div class="panel-body">
           <fieldset>
                <input type="hidden" name="conektaTokenId" id="conektaTokenId" value="-1">
                <input type="hidden" name="idProducto" id="conektaTokenId" value="{{$idProducto}}">
                <input type="hidden" name="tipo_pago" id="tipo_pago" value="3">
                <legend>Datos para Notificar Pag&oacute;:</legend>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="card-holder-name">Nombre </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                      <button type="submit" class="btn btn-success">Crear Referencia</button>
                  </div>
                </div>
              </fieldset> 
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection