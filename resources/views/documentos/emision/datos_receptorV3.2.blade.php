@extends('layouts.app')
@section('content')

<link href="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/js/select2.min.js"></script>
<script type="text/javascript">
           jQuery(document).ready(function($) {
                $('select').select2();
                var cmbEstado="{{$doc->cmbEstado}}"
                var codigoPostal="{{$doc->codigoPostal}}"
                //inicial
                cajaHide();
                cajaCmbShow();
                if(cmbEstado!=0){
                    $("#b_codigo_postal").val(codigoPostal);
                    buscarPorCodigoPostal();
                }

       });
       
function cambioPais(value){
    if(value==1){
        cajaHide();
        cajaCmbShow();
    }else{
        cajaCmbHide();
        cajaShow();
    }
} 
function agregarDireccion(){
    limpiarContribuyente();
    cajaCmbHide();
    cajaShow();
}
function regresarCombo(){
    cajaHide();
    cajaCmbShow();
}
function cajaHide(){
        $("#caja_estado").hide();
        $("#caja_municipio").hide();
        $("#caja_colonia").hide();
}
function cajaCmbHide(){
        $("#caja_cmb_estado").hide();
        $("#caja_cmb_municipio").hide();
        $("#caja_cmb_colonia").hide();
}
function cajaShow(){
        $("#caja_estado").show();
        $("#caja_municipio").show();
        $("#caja_colonia").show();
}
function cajaCmbShow(){
        $("#caja_cmb_estado").show();
        $("#caja_cmb_municipio").show();
        $("#caja_cmb_colonia").show();
}
//$('select').select2();
   
function cambioMunicipio(value) {       
        $("#cmbEstado > option").each(function() {
            if(this.value==value){
                $("[name='estado']").val(this.text);
            }
        });
        $.ajax({
            url: "{{ url('/domicilio/comboMunicipoPorIdEdo') }}",
            headers: {
                'X-CSRF-Token':'{{ csrf_token() }}'
            },
            method: 'GET',
            dataType: 'json',
            async: false,
            data: {'id_estado': value},
            success: function(data){
                var id = 'cmbMunicipio';
                var select = $('#'+id);
                select.children('option:not(:first)').remove();
                if (data.length > 0) {
                    addComboArray(id, data);
                }else{
                    $("#select2-cmbMunicipio-container").html("--Selecciona--");
                }  
            }
          });
          
}

function cambioColonia(value) { 
     $("#cmbMunicipio > option").each(function() {
        if(this.value==value){
            $("[name='municipio']").val(this.text);
        }
    });
    $.ajax({
            url: "{{ url('/domicilio/comboColoniaPorIdMunicipio') }}",
            headers: {
                'X-CSRF-Token':'{{ csrf_token() }}'
            },
            method: 'GET',
            dataType: 'json',
            async: false,
            data: {'id_municipio': value},
            success: function(data){
                var id = 'cmbColonia';
                var select = $('#'+id);
                select.children('option:not(:first)').remove();
                if (data.length > 0) {
                    addComboArray(id, data);
                }else{
                    $("#select2-cmbColonia-container").html("--Selecciona--");
                } 
            }
          });
}
function cambioCodigoPostal(value){
        $("#cmbColonia > option").each(function() {
            if(this.value==value){
                $("[name='colonia']").val(this.text);
            }
        });
        $.ajax({
            url: "{{ url('/domicilio/codigoPostalPorIdColonia') }}",
            headers: {
                'X-CSRF-Token':'{{ csrf_token() }}'
            },
            method: 'GET',
            dataType: 'json',
            data: {'id_colonia': value},
            success: function(data){
                $("#codigo_postal").val(''); 
                $("#codigo_postal").val(data.nombre);  
            }
          });
}
function addComboArray(idDiv, values) {
    for (var x in values) {
        var txt = '<option value="' + values[x].id + '">' + values[x].nombre + '</option>';
        $("#" + idDiv).append(txt);
    }
}

function buscarPorCodigoPostal(){
    var cp = $("#b_codigo_postal").val();
    $.ajax({
            url: "{{ url('/domicilio/direccionPorCodigoPostal') }}",
            headers: {
                'X-CSRF-Token':'{{ csrf_token() }}'
            },
            method: 'GET',
            dataType: 'json',
            data: {'codigo_postal': cp},
            success: function(data){
                var notLocalization = (data.id_pais==undefined?true:false);
                data.id_pais=(data.id_pais==undefined?1:data.id_pais);
                data.id_estado=(data.id_estado==undefined?0:data.id_estado);
                data.id_municipio_delg=(data.id_municipio_delg==undefined?0:data.id_municipio_delg);
                data.id_colonia=(data.id_colonia==undefined?0:data.id_colonia);
                seleccionaPais(data.id_pais,null);
                seleccionaEstado(data.id_estado,null);
                seleccionaMunicipio(data.id_municipio_delg,null);
                seleccionaColonia(data.id_colonia,null);

                if(notLocalization){
                    alert("No se localiza direccion.");
                }
            }
          });
}
function limpiarContribuyente(){
    $("[name='rfc']").val('');
    $("[name='razon_social']").val('');
    $("[name='calle']").val('');
    $("[name='num_ext']").val('');
    $("[name='num_int']").val('');
    $("[name='localidad']").val('');
    $("[name='codigo_postal']").val('');
    $("[name='municipio']").val('');
    $("[name='colonia']").val('');
    $("[name='estado']").val('');
    $("[name='cel']").val('');
    $("[name='correo_electronico']").val('');
}
function seleccContribuyente(value){
    limpiarContribuyente();
    var rowData = $("#grid-contribuyentes").getRowData(value);
    console.log(rowData);
    regresarCombo()
    $("[name='rfc']").val(rowData.bRfc);
    $("[name='razon_social']").val(rowData.bRazonSocial);
    $("[name='calle']").val(rowData.bCalle);
    $("[name='num_ext']").val(rowData.bNumExt);
    $("[name='num_int']").val(rowData.bNumInt);
    $("[name='localidad']").val(rowData.bLocalidad);
    $("[name='codigo_postal']").val(rowData.bCodigoPostal);
    seleccionaPais(rowData.idPais,rowData.bPais);
    if(rowData.bIdEstado=="" || rowData.bIdEstado==undefined){
        cajaCmbHide();
        cajaShow();
        $("[name='estado']").val(rowData.bEstado);
        $("[name='municipio']").val(rowData.bMunicipio);
        $("[name='colonia']").val(rowData.bColonia);
    }else{  
        seleccionaEstado(rowData.idEstado,rowData.bEstado);
        seleccionaMunicipio(rowData.idMunicipio,rowData.bMunicipio);
        seleccionaColonia(rowData.idColonia,rowData.bColonia);
    }
    $("[name='cel']").val(rowData.bCelular);
    $("[name='correo_electronico']").val(rowData.bCorreo);
    $("#buscaContribuyente .close").click();
}

function seleccionaPais(idPais,namePais){
    $("#pais > option").each(function() {
        if(this.value==idPais || this.text==namePais){
            $(this).prop('selected', true); 
            $("#select2-pais-container").html(this.text);
            cambioPais(this.value);
        }
    });
    $("#pais").trigger('change');
}

function seleccionaEstado(idEstado,nameEstado){
    $("#cmbEstado > option").each(function() {
        if(this.value==idEstado || this.text==nameEstado){
            $(this).prop('selected', true); 
            $("#select2-cmbEstado-container").html(this.text); 
            $("[name='estado']").val(this.text);
        }
    });
    $("#cmbEstado").trigger('change');
}
function seleccionaMunicipio(idMunicipio,nameMunicipio){
    $("#cmbMunicipio > option").each(function() {
        if(this.value==idMunicipio || this.text==nameMunicipio){
            $(this).prop('selected', true); 
            $("#select2-cmbMunicipio-container").html(this.text); 
            $("[name='municipio']").val(this.text);
        }
    });
    $("#cmbMunicipio").trigger('change');
}
function seleccionaColonia(idColonia,nameColonia){
    $("#cmbColonia > option").each(function() {
        if(this.value==idColonia || this.text==nameColonia){
            $(this).prop('selected', true); 
            $("#select2-cmbColonia-container").html(this.text);
            $("[name='colonia']").val(this.text);
        }
    });
    $("#cmbColonia").trigger('change');
}
</script>

<!--selecciona tipo de documentos-->
<style type="text/css">
    .spacer5 {
        height: 5px;
    }
    @media screen and (min-width: 768px) {
        .modal-dialog {
            width: 700px; /* New width for default modal */
        }
        .modal-sm {
            width: 350px; /* New width for small modal */
        }
    }
    @media screen and (min-width: 992px) {
        .modal-lg {
            width: 1150px; /* New width for large modal */
        }
    }  
</style>
<div class="container">
{{ Form::open(array('url' => 'documentos/emision/conceptosTotales','method' => 'post')) }}    
  <div class="row">
      <div class="col-sm-8">
            <h2>Datos del Receptor</h2>
            <p>Se requieren datos del Receptor sobre {{$docNombre}}</p>
      </div>
      <div class="col-sm-4">
          <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
            aria-valuemin="0" aria-valuemax="100" style="width:40%">
              60% Completo
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
      <div class="panel-heading">Datos Contribuyente</div>
      <div class="panel-body">
          <div class="row">
              <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*R.F.C.:</span>
                    {!! Form::text('rfc',$doc->rfc,array('class' => 'form-control','placeholder'=>'Registro Federal del Contribuyente')) !!}
                </div>  
              </div>
              <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Razón Social:</span>
                    {!! Form::text('razon_social',$doc->razonSocial,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Razon Social')) !!}
                </div>  
              </div>
               
          </div>
          <div class="spacer5" ></div>
          <div class="row">
              <div class="col-sm-10"></div>
              <div class="col-sm-2">
                  <button type="button" data-toggle="modal" data-target="#buscaContribuyente" class="btn btn-info pull-right">
                    <span class="glyphicon glyphicon-search"></span> Buscar
                  </button>
              </div>
          </div>
          <div class="row">
              <!-- Modal -->
              <div class="modal fade" id="buscaContribuyente" role="dialog">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                              @include('documentos.emision.busca_contribuyente')
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
          
      </div>
      
<div class="panel panel-success">
      <div class="panel-heading">Domicilio Contribuyente</div>
      <div class="panel-body">
          <div class="row" >
              <div class="col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*calle:</span>
                    {!! Form::text('calle',$doc->calle,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Calle')) !!}
                </div>  
              </div> 
          </div>
          <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Nro Ext:</span>
                    {!! Form::text('num_ext',$doc->numExt,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Numero Exterior')) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">Nro Int:</span>
                    {!! Form::text('num_int',$doc->numInt,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Numero Interior')) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">Localidad:</span>
                    {!! Form::text('localidad',$doc->localidad,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Localidad')) !!}
                </div>  
              </div> 
            </div>
          <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Pais:</span>
                    {!! Form::select('pais', (['0' => '--Selecciona--'] + $listPais->toArray()), $doc->pais, ['class' => 'form-control','id'=>'pais','onchange' => 'cambioPais(this.value);']) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Estado:</span>
                    <div id="caja_cmb_estado" >
                    {!! Form::select('cmbEstado', (['0' => '--Selecciona--'] + $listEstado->toArray()), null, ['class' => 'form-control','id'=>'cmbEstado','onchange' => 'cambioMunicipio(this.value);']) !!}
                    </div>
                    <div id="caja_estado" >
                    {!! Form::text('estado',$doc->estado,array('class' => 'form-control','style'=>'text-transform: uppercase;','id'=>'estado','placeholder'=>'Estado')) !!}
                    </div>
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Municipio:</span>
                    <div id="caja_cmb_municipio" >
                    {!! Form::select('cmbMunicipio', (['0' => '--Selecciona--']), null, ['class' => 'form-control','id'=>'cmbMunicipio','onChange' => 'cambioColonia(this.value);' ]) !!}
                    </div>
                    <div id="caja_municipio" >
                    {!! Form::text('municipio',$doc->municipio,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Municipio')) !!}
                    </div>
                </div>  
              </div> 
            </div>
            
            <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Colonia:</span>
                    <div id="caja_cmb_colonia" >
                    {!! Form::select('cmbColonia', (['0' => '--Selecciona--']), null, ['class' => 'form-control','id'=>'cmbColonia','onChange' => 'cambioCodigoPostal(this.value);' ]) !!}
                    </div>
                    <div id="caja_colonia" >
                    {!! Form::text('colonia',$doc->colonia,array('class' => 'form-control','style'=>'text-transform: uppercase;','placeholder'=>'Colonia')) !!}
                    </div>
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Codigo Postal:</span>
                    {!! Form::text('codigo_postal',$doc->codigoPostal,array('class' => 'form-control','style'=>'text-transform: uppercase;','id'=>'codigo_postal','placeholder'=>'Codigo Postal')) !!}
                </div>  
              </div>
            </div>
          <div class="row" >
              <div class="col-sm-12" ><br/></div>
          </div>
          <div class="row" >
              <div class="col-sm-8" >
                    <a href="#buscar_cp" class="btn btn-info" data-toggle="collapse"><span class="glyphicon glyphicon-search"></span> Buscar Dirección Por Código Postal</a>
                    <div class="spacer5" ></div>
                    <div id="buscar_cp" class="collapse">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="input-group">
                              <span class="input-group-addon alert-danger">*Codigo Postal:</span>
                              {!! Form::text('b_codigo_postal',null,array('class' => 'form-control','id'=>'b_codigo_postal')) !!}
                          </div>  
                        </div>
                          <div class="col-sm-6">
                              {!! Form::button('Ok',['class' => 'btn btn-info','onClick' => 'buscarPorCodigoPostal();']) !!}
                          </div>  
                      </div>
                    </div>
                  
              </div>
              <div class="col-sm-2" >
                  <button type="button" class="btn btn-info" onclick="agregarDireccion();" >Agregar Dirección</button>
              </div>
              <div class="col-sm-2" >
                  <button type="button" class="btn btn-info" onclick="regresarCombo();" >regresar Dirección</button>
              </div>
          </div>
        </div>
          
      </div>      
      <div class="panel panel-success">
      <div class="panel-heading">Notificaciones al Receptor</div>
      <div class="panel-body">
          <div class="row">
              <div class="col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon">Correo Electr&oacute;nico {{$docNombre}}:</span>
                    {!! Form::text('correo_electronico',$doc->email,array('class' => 'form-control','placeholder'=>'E-Mail')) !!}
                </div>  
              </div>
          </div>    
          <div class="row">    
              <div class="col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon">Celular {{$docNombre}}:</span>
                    {!! Form::number('cel',$doc->cell,['maxlength'=>'10','min'=>'1','class' => 'form-control','placeholder'=>'Movil']) !!}
                </div>  
              </div>
          </div>
        </div>
          
      </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-6">
                <a href="{{url('/documentos/tipo')}}/0/{{Crypt::encrypt('1')}}" 
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
@endsection