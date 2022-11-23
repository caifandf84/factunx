@extends('layouts.app')
@section('content')
<!--es version 1.0.0 funciona correctamente-->
<link href="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/js/select2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
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
          <ul class="list-group">
            <li class="list-group-item justify-content-between">
              Tipo de Documento
              <span class="badge badge-default badge-pill" id="tipoDocTxt" ></span>
            </li>
          </ul>
      </div>
      <div class="panel-body">
          {!! Form::hidden('tipoDoc', '0',array('id' => 'tipoDoc')) !!}
          @if (count($tipoDocs) > 3)
          <div class="btn-group btn-group-justified">
            @foreach ($tipoDocs as $indexKey => $tipoDoc)
                @if ($indexKey < 4)
                <a href="#" onclick="asignaTipoDoc('{{$tipoDoc->id}}')"  id="btn_tipo_doc{{$tipoDoc->id}}" class="btn btn-primary">{{$tipoDoc->nombre}}</a>
                @endif
            @endforeach
          </div>
          @endif
          @if (count($tipoDocs) > 7)
          <div class="btn-group btn-group-justified">
            @foreach ($tipoDocs as $indexKey => $tipoDoc)
                @if ($indexKey > 3 && $indexKey <8)
                <a href="#" onclick="asignaTipoDoc('{{$tipoDoc->id}}')" id="btn_tipo_doc{{$tipoDoc->id}}" class="btn btn-primary">{{$tipoDoc->nombre}}</a>
                @endif
            @endforeach 
          </div>
          @endif
          @if (count($tipoDocs) > 11)
          <div class="btn-group btn-group-justified">
            @foreach ($tipoDocs as $indexKey => $tipoDoc)
                @if ($indexKey > 8 && $indexKey <12)
                <a href="#" onclick="asignaTipoDoc('{{$tipoDoc->id}}')" id="btn_tipo_doc{{$tipoDoc->id}}" class="btn btn-primary">{{$tipoDoc->nombre}}</a>
                @endif
            @endforeach 
          </div>
          @endif          
      </div>
    </div>
    <div class="panel panel-success">
      <!--div class="panel-heading">Panel Header</div-->
      <div class="panel-body">
          <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon ">Serie:</span>
                    {!! Form::text('serie',$doc->serie,array('class' => 'form-control','placeholder'=>'Serie')) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">Numero:</span>
                    {!! Form::text('numero',$doc->numero,array('class' => 'form-control','placeholder'=>'Numero')) !!}
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
                    <!--input id="fecha_emision" type="datetime-local" class="form-control" name="fecha_emision" placeholder="Fecha de Emisión"-->
                </div>  
              </div>
          </div>
          <hr/>
          <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">Forma de Pago:</span>
                    {!! Form::select('forma_pago', (['0' => '--Selecciona--'] + $listFormaPago->toArray()), $doc->idFormaPago, ['class' => 'form-control']) !!}
                    <!-- input id="forma_pago" type="text" class="form-control" name="forma_pago" placeholder="Forma de Pago"-->
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">Tipo de Cambio:</span>
                    {!! Form::number('tipo_cambio', $doc->tipoCambio, ['placeholder' => 'Tipo de Cambio']) !!}
                    <!--input id="tipo_cambio" type="text" class="form-control" name="tipo_cambio" placeholder="Tipo de Cambio"-->
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">Moneda:</span>
                    {!! Form::select('moneda', (['0' => '--Selecciona--'] + $listMoneda->toArray()), $doc->moneda, ['class' => 'form-control']) !!}
                </div>  
              </div>
          </div>
          <hr/>
          <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">Metodo de Pago:</span>
                    {!! Form::select('metodo_pago', (['0' => '--Selecciona--'] + $listMetodoPago->toArray()), $doc->metodoPago, ['class' => 'form-control']) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">Condiciones de Pago:</span>
                    {!! Form::textarea('condiciones_pago', $doc->condicionesPago, ['size' => '40x2']) !!}
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
                    <!--button type="button" class="btn btn-info">Siguiente</button-->
                </div> 
            </div> 
        </div>
    </div>
  </div>
{{ Form::close() }}
</div>
<script>
function asignaTipoDoc(value){
    $("#tipoDoc").val(value);
    $("#tipoDocTxt").html(getNombreTipoDocById(value));
}
var tipoDocsArray =[];

function getNombreTipoDocById(value){
    for (i = 0; i < tipoDocsArray.length; i++) { 
        if(tipoDocsArray[i].id==value){
            return tipoDocsArray[i].nombre;
        }
    }
    return "";
}

$(document).ready(function() {
    $('select').select2(); 
    $('#dp_fecha_emision').datetimepicker({locale:'es',defaultDate: new Date()});
    @foreach ($tipoDocs as $indexKey => $tipoDoc)
        var data={id:'{{$tipoDoc->id}}',nombre:'{{$tipoDoc->nombre}}'};
        tipoDocsArray.push(data);
    @endforeach 
    var idDoc="{{$doc->idTipoDoc}}"
    if(idDoc!=''){
        var id="#btn_tipo_doc"+idDoc;
        $(id).click();
        asignaTipoDoc(idDoc);
    }
});


</script>
@endsection
