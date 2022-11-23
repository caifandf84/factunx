@extends('layouts.app')

@section('content')
<script type="text/javascript" >
    $(document).ready(function () {
        $('#isEdit').val("-1");
        $('#isRemove').val("-1");
    });
    function editaSerieNumero(id){
        $('#isRemove').val("-1");
        $('#isEdit').val(id);
        var rowData = $("#grid-b-serie-numero").getRowData(id);
        $('#serie').val(rowData.bSerie);
        $('#numero').val(rowData.bNumero);
    }
    function eliminaSerieNumero(id){
        $('#isRemove').val(id);
        $('#isEdit').val("-1");
        $("#form_serie_numero").submit();
    }
    function borrarCampos(){
        $('#serie').val('');
        $('#numero').val('');
    }
</script>
<div class="container">
    @isset($mensaje)
    <div class="row">
        <ul class="list-group" >
            <li class="list-group-item list-group-item-success" >{{ $mensaje }}</li>
        </ul>
    </div> 
    @endisset
    <div class="alert alert-info" >
        <strong>Serie - Folio/N&uacute;mero</strong><br/>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            {{ Form::open(array('url' => 'configuracion/serie_numero/crud','method' => 'post','id'=>'form_serie_numero')) }}
            {{ Form::hidden('isEdit', '-1', array('id' => 'isEdit')) }}
            {{ Form::hidden('isRemove', '-1', array('id' => 'isRemove')) }}
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">
                            <span class="input-group-addon alert-danger">*Serie:</span>
                            {!! Form::text('serie',null,array('id'=>'serie','class' => 'form-control','placeholder' => 'Serie')) !!}
                        </div>  
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <span class="input-group-addon alert-danger">*Folio/N&uacute;mero:</span>
                        {!! Form::number('numero',null,['id'=>'numero','min'=>'1','class' => 'form-control','placeholder' => 'Folio/Número']) !!}
                    </div>  
                </div>
            </div>
            <div class="row">
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="pull-right">
                        {!! Form::button('Borrar',['class' => 'btn btn-info','onClick' => 'borrarCampos();']) !!}
                    </div> 
                </div> 
                <div class="col-sm-6">
                    <div class="pull-right">
                        {!! Form::submit('Guardar',['class' => 'btn btn-info']) !!}
                    </div> 
                </div> 
            </div>
            {{ Form::close() }}
            <div class="row">
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @include('documentos.emision.serie_numero.buscar',[
                    'seleccion' => true,
                    'editar' => false,
                    'eliminar' => false
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection