@extends('layouts.app')
@section('content')

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
<script type="text/javascript" >  
$(document).ready(function () {
    var idDoc = "{{$doc->idTipoDoc}}"
    if (idDoc == 74){
            $("#uso_cfdi").val("P01").trigger('change');
            $('#uso_cfdi option:not(:selected)').prop('disabled', true).trigger('change');
    }else if(idDoc == 75){
            $("#uso_cfdi").val("P01").trigger('change');
            $('#uso_cfdi option:not(:selected)').prop('disabled', true).trigger('change');
            $("#tipo_relacion").val(4).trigger('change');
            $('#tipo_relacion option:not(:selected)').prop('disabled', true).trigger('change');
    }else{
        $('#uso_cfdi option:not(:selected)').prop('disabled', false).trigger('change');
    }
});  
function cambioMunicipio(value) {       
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
function cambioPais(value){
    if(value!=1){
        $("#codigo_postal").val(''); 
        $('#cmbColonia option:eq(0)');
        $('#cmbEstado option:eq(0)');
        $('#cmbMunicipio option:eq(0)');
    }
} 
function addComboArray(idDiv, values) {
    for (var x in values) {
        var txt = '<option value="' + values[x].id + '">' + values[x].nombre + '</option>';
        $("#" + idDiv).append(txt);
    }
}
function seleccContribuyente(value){
    limpiarContribuyente();
    var rowData = $("#grid-contribuyentes").getRowData(value);
    $("[name='rfc']").val(rowData.bRfc);
    $("[name='razon_social']").val(rowData.bRazonSocial);
    $("[name='codigo_postal']").val(rowData.bCodigoPostal);
    $("[name='cel']").val(rowData.bCelular);
    $("[name='correo_electronico']").val(rowData.bCorreo);
    $("#buscaContribuyente .close").click();
}
function seleccDocumento(value){
    //var rowData = $("#grid-contribuyentes").getRowData(value);
    $("[name='uuid_relacionado']").val('');
    $("[name='uuid_relacionado']").val(value);
    $("#buscaDocumento .close").click();
}
function limpiarContribuyente(){
    $("[name='rfc']").val('');
    $("[name='razon_social']").val('');
    $("[name='codigo_postal']").val('');
    $("[name='cel']").val('');
    $("[name='correo_electronico']").val('');
}
</script>
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
                            {!! Form::text('razon_social',$doc->razonSocial,array('class' => 'form-control','placeholder'=>'Razon Social')) !!}
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
            <div class="panel-heading">Domicilio y Motivo</div>
            <div class="panel-body">
                <div class="row" >
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Codigo Postal:</span>
                            {!! Form::text('codigo_postal',$doc->codigoPostal,array('class' => 'form-control','style'=>'text-transform: uppercase;','id'=>'codigo_postal','placeholder'=>'Codigo Postal')) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <a href="#buscar_direccion" class="btn btn-info" data-toggle="collapse"><span class="glyphicon glyphicon-search"></span> Buscar Por Direcci&oacute;n</a>  
                    </div>
                </div>
                <div id="buscar_direccion" class="collapse">
                    <div class="spacer5" ></div>
                    <fieldset>
                    <legend>Buscar Codigo Postal:</legend>
                        <div class="row" >
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon alert-info">Pais:</span>
                                    {!! Form::select('pais', (['0' => '--Selecciona--'] + $listPais->toArray()), $doc->pais, ['class' => 'form-control','id'=>'pais','onchange' => 'cambioPais(this.value);','disabled' => 'disabled']) !!}
                                </div>  
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon alert-info">Estado:</span>
                                    <div id="caja_cmb_estado" >
                                    {!! Form::select('cmbEstado', (['0' => '--Selecciona--'] + $listEstado->toArray()), null, ['class' => 'form-control','id'=>'cmbEstado','onchange' => 'cambioMunicipio(this.value);']) !!}
                                    </div>
                                </div> 
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon alert-info">Municipio:</span>
                                    <div id="caja_cmb_municipio" >
                                    {!! Form::select('cmbMunicipio', (['0' => '--Selecciona--']), null, ['class' => 'form-control','id'=>'cmbMunicipio','onChange' => 'cambioColonia(this.value);' ]) !!}
                                    </div>
                                </div>  
                            </div> 
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon alert-info">Colonia:</span>
                                    <div id="caja_cmb_colonia" >
                                    {!! Form::select('cmbColonia', (['0' => '--Selecciona--']), null, ['class' => 'form-control','id'=>'cmbColonia','onChange' => 'cambioCodigoPostal(this.value);' ]) !!}
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="spacer5" ></div>
                <div class="row" >
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Uso del Documento ({{$docNombre}}) :</span>
                            {!! Form::select('uso_cfdi', (['0' => '--Selecciona--'] + $listUsoCfdi->toArray()), $doc->usoCfdi, ['class' => 'form-control','id'=>'uso_cfdi']) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <p>El uso de documento es para que se va a usar el documento <strong>{{$docNombre}}</strong> y se solicita esa informaci&oacute;n al Receptor</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">Existe Relaci&oacute;n con otro Documento</div>
            <div class="panel-body">
                <div class="row" >
                    <div class="col-sm-12">
                        <p>Sí requiere relacionar un Documento para cancelar, facturar en varias exibiciones llenar los campos siguientes,</p>
                        <p><strong>En caso de no tener una relacion con otro Documento no llenar los campos</strong></p>
                    </div> 
                </div>
                <div class="row" >
                    <div class="col-sm-6">
                        
                        @if ($doc->idTipoDoc == 75)
                            <div class="input-group">
                                <span class="input-group-addon alert-danger">*Motivo Relaci&oacute;n UUID:</span>
                                {!! Form::select('tipo_relacion', (['0' => '--Selecciona--'] + $tipoRelaciones->toArray()), $doc->tipoRelacion, ['class' => 'form-control','id'=>'tipo_relacion']) !!}
                            </div>   
                        @else
                            <div class="input-group">
                                <span class="input-group-addon">Motivo Relaci&oacute;n UUID:</span>
                                {!! Form::select('tipo_relacion', (['0' => '--Selecciona--'] + $tipoRelaciones->toArray()), $doc->tipoRelacion, ['class' => 'form-control','id'=>'tipo_relacion']) !!}
                            </div> 
                        @endif
                    </div>
                    <div class="col-sm-6">
                        @if ($doc->idTipoDoc == 75)
                            <div class="input-group">
                                <span class="input-group-addon alert-danger">*UUID Relacionado:</span>
                                {!! Form::text('uuid_relacionado',$doc->uuidRelacionado,array('class' => 'form-control','id'=>'uuid_relacionado','placeholder'=>'Uuid Relacionado')) !!}
                            </div>  
                        @else
                            <div class="input-group">
                                <span class="input-group-addon">UUID Relacionado:</span>
                                {!! Form::text('uuid_relacionado',$doc->uuidRelacionado,array('class' => 'form-control','id'=>'uuid_relacionado','placeholder'=>'Uuid Relacionado')) !!}
                            </div> 
                        @endif
                    </div>
                </div>
                <div class="spacer5" ></div>


                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <button type="button" data-toggle="modal" data-target="#buscaDocumento" class="btn btn-info pull-right">
                            <span class="glyphicon glyphicon-search"></span> Buscar
                        </button>
                    </div>
                </div>
                <div class="row">
                    <!-- Modal -->
                    <div class="modal fade" id="buscaDocumento" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    @include('documentos.emision.busca_documento')
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
        <!--div class="panel panel-success">
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
                  
              </div-->      
        <div class="panel panel-success">
            <div class="panel-heading">Notificaciones al Receptor</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">Correo Electr&oacute;nico {{$docNombre}}:</span>
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

