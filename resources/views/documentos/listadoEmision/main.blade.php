@extends('layouts.app')
@section('content')
<!--muestra lista de documentos-->
<link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css">
<link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.theme.css">
<!-- Scripts -->
<script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<link href="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script> 
<div class="container" >
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">Documentos timbrados ante el SAT</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Nombre Cliente:</span>
                            {!! Form::text('nombreCliente',null,array('id'=>'nombreCliente','class' => 'form-control','placeholder'=>'Nombre')) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">R.F.C. Cliente:</span>
                            {!! Form::text('rfcCliente', null, ['id'=>'rfcCliente','class' => 'form-control','placeholder' => 'Rfc']) !!}
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Serie:</span>
                            {!! Form::text('serie',null,array('id'=>'serie','class' => 'form-control','placeholder'=>'Serie')) !!}
                        </div>  
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Folio:</span>
                            {!! Form::text('folio', null, ['id'=>'folio','class' => 'form-control','placeholder' => 'Folio']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha Desde:</span>
                            <div class='input-group date' id='dp_fecha_desde'  >
                                {!! Form::text('fechaDesde', null, ['id'=>'fechaDesde','class' => 'form-control']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div> 
                        </div>  
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Fecha Hasta:</span>
                            <div class='input-group date' id='dp_fecha_hasta'  >
                                {!! Form::text('fechaHasta', null, ['id'=>'fechaHasta','class' => 'form-control']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div> 

                        </div>  
                    </div>
                </div>
                <div class="row">     
                    <div class="col-sm-6"> 
                    </div>
                    <div class="col-sm-3"> 
                        {!! Form::button('Limpiar',['class' => 'btn btn-info pull-right','onClick' => 'limpiarCampos();']) !!}  
                    </div>
                    <div class="col-sm-3">
                        {!! Form::button('Buscar',['class' => 'btn btn-info pull-right','onClick' => 'buscarDocumento();']) !!}  
                    </div>
                </div>
                <div class="row"> 
                    <hr>
                </div>
                <div class="row"> 
                    @include('documentos.listadoEmision.grid')
                </div>
            </div>
        </div>
    </div>   
</div>
<div id="dialog" title="Enviar Correo">
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">Correo:</span>
                {!! Form::text('correo', null, ['id'=>'correo','size'=>'30']) !!}
                <input type="hidden" value="1" id="idDocumento" name="idDocumento" >
            </div>  
        </div>
        <div class="col-sm-6">
            {!! Form::button('Enviar',['class' => 'btn btn-info pull-right','onClick' => 'enviarCorreo();']) !!} 
        </div>
    </div>
</div>    
<script type="text/javascript" >
    $(document).ready(function () {
        $('#dp_fecha_desde').datetimepicker({locale: 'es'});
        $('#dp_fecha_hasta').datetimepicker({locale: 'es'});
        $("#dialog").dialog({autoOpen: false});
    });

    function buscarDocumento() {
        var fechaHasta = $("#fechaHasta").val();
        var fechaDesde = $("#fechaDesde").val();
        var folio = $("#folio").val();
        var serie = $("#serie").val();
        var rfcCliente = $("#rfcCliente").val();
        var nombreCliente = $("#nombreCliente").val();
        var url = "{{ url('/documentos/listaGrid') }}?rfc_cliente=" + rfcCliente + "&nombre_cliente=" +
                nombreCliente + "&serie=" + serie + "&folio=" + folio + "&fecha_desde=" +
                fechaDesde + "&fecha_hasta=" + fechaHasta;
        jQuery("#grid-documentos").jqGrid('setGridParam',
                {url: url}).trigger("reloadGrid");
    }

    function limpiarCampos() {
        $("#fechaHasta").val('');
        $("#fechaDesde").val('');
        $("#folio").val('');
        $("#serie").val('');
        $("#rfcCliente").val('');
        $("#nombreCliente").val('');
    }

    function obtenerCorreo(id) {
         $("#idDocumento").val(id);
        $("#dialog").dialog({autoOpen: true,
            height: 150,
            width: 500});
    }

    function enviarCorreo() {
        var idDocumento=$("#idDocumento").val();
        var correo=$("#correo").val();
        $.ajax( {
          url: "{{ url('/documentos/enviaCorreo') }}",
          dataType: "json",
          data: {idEmisor:idDocumento,correo:correo},
          async: false,
          success: function( data ) {
            if(data!=null){
              $( "#dialog" ).dialog( "close" );
              alert(data); 
            }
            //console.log(data);  
          }
      });
    }

</script>
@endsection
