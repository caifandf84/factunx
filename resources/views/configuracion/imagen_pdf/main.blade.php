@extends('layouts.app')

@section('content')
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link href="https://www.conuxi.com/mascoota_files/fileinput-master/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="https://www.conuxi.com/mascoota_files/canvas-to-blob/js/canvas-to-blob.min.js"></script>
<script src="https://www.conuxi.com/mascoota_files/fileinput-master/js/fileinput.min.js"></script>
<script src="https://www.conuxi.com/mascoota_files/fileinput-master/js/fileinput_locale_es.js"></script>

<div class="container">
    @isset($mensaje)
    <div class="row">
        <ul class="list-group" >
            <li class="list-group-item list-group-item-success" >{{ $mensaje }}</li>
        </ul>
    </div> 
    @endisset
    <div class="alert alert-info" >
        <strong>Cambar Imagen PDF</strong><br/>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            {{ Form::open(array('url' => 'configuracion/imagen_empresa/actualizar','files'=>true,'method' => 'post','id'=>'form_img_empresa')) }}
            <div class="row">
                <div class="col-sm-12">
                    {!! Form::file('photo_create',['class' => 'file-loading','id' => 'input-image-1']) !!} 
                </div>
            </div>
            <div class="row">
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-6">
                </div> 
                <div class="col-sm-6">
                    <div class="pull-right">
                        {!! Form::submit('Guardar',['class' => 'btn btn-info']) !!}
                    </div> 
                </div> 
            </div>
            {{ Form::close() }}
            <div class="row">
                <div class="form-group">
                    <label class="control-label col-xs-2">{!! Form::label('photo_name', 'Imagen :') !!}</label>
                    <div class="col-xs-8">
                        <img src="{{$urlImg}}?idRandom='<?php echo rand(5, 50000) ?>'" class="img-thumbnail" alt="image" id="photo" width="150" height="120">
                    </div>
                </div>
            </div>
            <div class="row">
                <hr>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" >
    $("#input-image-1").fileinput({
    //uploadUrl: "/blog/publica/photo",
    language: 'es',
    uploadUrl: '#',
    allowedFileExtensions: ["jpg", "png", "gif"],
    maxImageWidth: 400,
    maxImageHeight: 300,
    maxFileCount: 1,
    resizeImage: true
}).on('filepreupload', function () {
    $('#kv-success-box').html('');
}).on('fileuploaded', function (event, data) {
    $('#kv-success-box').append(data.response.link);
    $('#kv-success-modal').modal('show');
});
    /*$(document).ready(function () {
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
    }*/
</script>
@endsection
