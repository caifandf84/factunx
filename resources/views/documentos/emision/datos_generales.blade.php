@extends('layouts.app')
@section('content')
<!--es version 1.0.0 funciona correctamente-->
<link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css">
<link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.theme.css">
<!-- Scripts -->
<script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.js"></script>

<link href="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<link href="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script>



<!--selecciona tipo de documentos-->
<div class="container">
    {{ Form::open(array('url' => 'documentos/emision/datosGeneral','method' => 'post')) }}    
    <div class="row">
        <div class="col-sm-8">
            <h2>Datos Generales</h2>
            <p>Datos generales para emitir un documento ante el SAT</p>
        </div>
        <div class="col-sm-4">
            <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                     aria-valuemin="0" aria-valuemax="100" style="width:40%">
                    40% Completo
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <ul class="list-group" >
            @foreach($errors->all() as $error)
            <li class="list-group-item list-group-item-danger" >{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <div class="panel-group">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row" >
                    <div class="col-sm-6">
                        <h4>Tipo de Documento</h4> 
                        {!! Form::hidden('tipoDoc', '0',array('id' => 'tipoDoc')) !!} 
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong class="text-center" ><div id="tipoDocTxt" ></div></strong></div>
                        </div>
                    </div>
                </div>
                <!--ul class="list-group">
                  <li class="list-group-item justify-content-between">
                    Tipo de Documento
                    <span class="badge badge-default banner-text" id="tipoDocTxt" ></span>
                  </li>
                </ul-->
            </div>
            <div class="panel-body">
                <div class="btn-group btn-group-justified">
                @foreach ($tipoDocs as $indexKey => $tipoDoc)
                    @if ($tipoDoc->id==71 || $tipoDoc->id==33 || $tipoDoc->id==75)
                    <a href="#" onclick="asignaTipoDoc('{{$tipoDoc->id}}')"  id="btn_tipo_doc{{$tipoDoc->id}}" class="btn btn-primary">{{$tipoDoc->nombre}}</a>
                    @endif
                @endforeach
                </div>    
            </div>       
      </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon ">Serie:</span>
                            {!! Form::text('serie',$doc->serie,array('class' => 'form-control','placeholder'=>'Serie','id'=>'serie')) !!}
                        </div>  
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Folio/Número:</span>
                            {!! Form::text('numero',$doc->numero,array('class' => 'form-control','placeholder'=>'Numero','id'=>'numero')) !!}
                        </div>  
                    </div>
                    <div class="col-sm-1">
                        <button type="button" data-toggle="modal" data-target="#buscaSerieNumero" class="btn btn-info pull-right">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="buscaSerieNumero" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        @include('documentos.emision.serie_numero.buscar',[
                                                    'seleccion' => false,
                                                    'editar' => true,
                                                    'eliminar' => true
                                                ])
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">Fecha de Emisión:</span>
                            <div class='input-group date' id='dp_fecha_emision'  >
                                {!! Form::text('fecha_emision',$doc->fechaEmision,array('id'=>'fecha_emision','class' => 'form-control')) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div> 
                        </div>  
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-sm-4">

                        <div class="input-group">
                            <span class="input-group-addon alert-danger">Forma de Pago:</span>
                            {!! Form::select('forma_pago', (['0' => '--Selecciona--'] + $listFormaPago->toArray()), $doc->idFormaPago, ['class' => 'form-control','id'=>'forma_pago']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <h4>Desea Facturar con Moneda <strong>diferente</strong> a <strong>Pesos Mexicanos</strong></h4> 
                    </div>
                    <div class="col-sm-2">
                        <div class="container">
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalCambioMoneda">Cambiar</button>
                            <!-- Modal -->
                            <div class="modal fade" id="modalCambioMoneda" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Tipo de Cambio en Monedas</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row" >
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon alert-danger">Tipo de Cambio:</span>
                                                        {!! Form::number('tipo_cambio', $doc->tipoCambio, ['placeholder' => 'Tipo de Cambio']) !!}
                                                    </div>  
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon alert-danger">Moneda:</span>
                                                        {!! Form::select('moneda', (['0' => '--Selecciona--'] + $listMoneda->toArray()), $doc->moneda, ['class' => 'form-control','style'=>'width: 300px;','id'=>'moneda']) !!}
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">Metodo de Pago:</span>
                            {!! Form::select('metodo_pago', (['0' => '--Selecciona--'] + $listMetodoPago->toArray()), $doc->metodoPago, ['class' => 'form-control','id'=>'metodo_pago','onchange' => 'cambioTipoComprobante();']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">Tipo de Comprobante:</span>
                            {!! Form::select('tipo_comprobante', (['0' => '--Selecciona--'] + $listTipoComprobante->toArray()),$doc->tipoComprobante,  ['class' => 'form-control','id'=>'tipo_comprobante']) !!}
                        </div>  
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon">Condiciones de Pago:</span>
                            {!! Form::textarea('condiciones_pago', $doc->condicionesPago, ['class' => 'form-control','size' => '120x3','id'=>'condiciones_pago']) !!}
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-6">
                    <a href="{{ url('/documentos') }}" 
                       class="btn btn-info">Atras</a>
                </div>
                <div class="col-sm-6">
                    <div class="pull-right">
                        {!! Form::submit('Siguiente',['class' => 'btn btn-info']) !!}
                    </div> 
                </div> 
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
<script type="text/javascript" >
function seleccSerieNumero(id){
    var rowData = $("#grid-b-serie-numero").getRowData(id);
    $('#serie').val(rowData.bSerie);
    var numero =Number(rowData.bNumero);
    numero = (numero==1?numero:numero+1);
    $('#numero').val(numero);
    $("#buscaSerieNumero .close").click();
}    
function asignaTipoDoc(value) {
    $("#tipoDoc").val(value);
    $("#tipoDocTxt").html(getNombreTipoDocById(value));
    if (value == 74){
            $("#forma_pago").val("99").trigger('change');
            $('#forma_pago option:not(:selected)').prop('disabled', true).trigger('change');
            $("#metodo_pago").val("PUE").trigger('change');
            $('#metodo_pago option:not(:selected)').prop('disabled', true).trigger('change');
            $("#tipo_comprobante").val("N").trigger('change');
            $('#tipo_comprobante option:not(:selected)').prop('disabled', true).trigger('change');
            $("#condiciones_pago").val("").trigger('change');
            $('#condiciones_pago option:not(:selected)').prop('disabled', true).trigger('change');
    }else{
        var formaPago = "{{$doc->idFormaPago}}"
        $("#forma_pago").val(formaPago).trigger('change');
        $('#metodo_pago option:not(:selected)').prop('disabled', false).trigger('change');
        $('#forma_pago option:not(:selected)').prop('disabled', false).trigger('change');
        $('#tipo_comprobante option:not(:selected)').prop('disabled', false).trigger('change');
        $('#condiciones_pago option:not(:selected)').prop('disabled', false).trigger('change');
    }
    cambioTipoComprobante();
}
var tipoDocsArray = [];

function getNombreTipoDocById(value) {
    for (i = 0; i < tipoDocsArray.length; i++) {
        if (tipoDocsArray[i].id == value) {
            return tipoDocsArray[i].nombre;
        }
    }
    return "";
}

$(document).ready(function () {

    $('#dp_fecha_emision').datetimepicker({locale:'es', defaultDate: new Date()});
            @foreach ($tipoDocs as $indexKey => $tipoDoc)
    var data = {id: '{{$tipoDoc->id}}', nombre: '{{$tipoDoc->nombre}}'};
    tipoDocsArray.push(data);
    @endforeach
            var idDoc = "{{$doc->idTipoDoc}}"
    var tipoComp = "{{$doc->tipoComprobante}}"
    var metodoPago = "{{$doc->metodoPago}}"
    if (idDoc != '') {
        if (idDoc == 33 && tipoComp == "" && metodoPago == "PUE") {
            $("[name='tipo_comprobante']").val("I");
        }else if(idDoc == 75){
            $("[name='tipo_comprobante']").val("P");
        }
        var id = "#btn_tipo_doc" + idDoc;
        $(id).click();
        asignaTipoDoc(idDoc);
    }
    $('select').select2();
});

function cambioTipoComprobante() {

    var metodoPago = $("#metodo_pago").val();
    var tipoDoc = $("#tipoDoc").val();
    if (metodoPago == "PUE" && tipoDoc == 33) {
        cambioComboTComprobante("I");
    } else if (metodoPago == "PPD" && tipoDoc == 33) {
        cambioComboTComprobante("P");
    } else if (tipoDoc == 75){
        cambioComboTComprobante("P");
    }
}
function cambioComboTComprobante(id) {
    $("#tipo_comprobante > option").each(function () {
        if (this.value == id) {
            $(this).prop('selected', true);
            $("#tipo_comprobante").val(id);
            $("#select2-tipo_comprobante-container").html(this.text);
        }
    });
    $("#tipo_comprobante").trigger('change');
}
</script>
@endsection
