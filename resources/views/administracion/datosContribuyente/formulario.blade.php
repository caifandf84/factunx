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

@isset($mensaje)
<div class="row">
    <ul class="list-group" >
        <li class="list-group-item list-group-item-info" >{{ $mensaje }}</li>
    </ul>
</div>
@endisset
<div class="row">
    <ul class="list-group" >
        @foreach($errors->all() as $error)
        <li class="list-group-item list-group-item-danger" >{{ $error }}</li>
        @endforeach
    </ul>
</div>
@if(!$disable) 
@isset($nuevo)
{{ Form::open(array('url' => '/registrar/contribuyente/seleccionado','method' => 'post','enctype'=>'multipart/form-data')) }} 
{{ Form::hidden('tipo_registro', 'Nuevo') }}
@else
{{ Form::open(array('url' => 'administracion/actualizar/contribuyente','method' => 'post','enctype'=>'multipart/form-data')) }}  
@endisset
@endif
<div class="panel panel-success">
    <div class="panel-heading">Datos Contribuyente ****************EMISOR****************</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*R.F.C.:</span>
                    {!! Form::text('rfc',$contribuyente->rfc,array('id'=>'rfc','class' => 'form-control','disabled' => $disable,'placeholder'=>'Registro Federal del Contribuyente')) !!}
                </div>  
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Razón Social:</span>
                    {!! Form::text('razon_social',$contribuyente->razon_social,array('id'=>'razon_social','disabled' => $disable,'class' => 'form-control','placeholder'=>'Razon Social')) !!}
                </div>  
            </div>

        </div>
        <div class="spacer5" ></div>
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Tipo de Regimen:</span>
                    {!! Form::select('tipo_regimen',['0' => '--Selecciona--','F'=>'Física','M'=>'Moral'], $contribuyente->tipo_regimen, ['class' => 'form-control','disabled' => $disable,'id'=>'tipo_regimen','onchange' => 'cambioRegimenFiscal(this.value);']) !!}
                </div>  
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Regimen Fiscal:</span>
                    {!! Form::select('regimen_fiscal',['0' => '--Selecciona--'], $contribuyente->regimen_fiscal, ['class' => 'form-control','disabled' => $disable,'id'=>'regimen_fiscal']) !!}
                </div>  
            </div>

        </div>
        <div class="spacer5" ></div>
        @isset($nuevo)
        @else
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">Archivo CER:</span>
                    {{ Form::hidden('id_archivo_cer', $contribuyente->id_archivo_cer, array('id' => 'id_archivo_cer')) }}
                    {!! Form::text('cer_texto',$contribuyente->archivo_cer,array('id'=>'cer_texto','disabled' => true,'class' => 'form-control')) !!}
                </div>  
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">Archivo KEY:</span>
                    {{ Form::hidden('id_archivo_key', $contribuyente->id_archivo_key, array('id' => 'id_archivo_key')) }}
                    {!! Form::text('key_texto',$contribuyente->archivo_key,array('id'=>'key_texto','disabled' => true,'class' => 'form-control')) !!}
                </div> 
            </div>
        </div>
        @endisset
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Archivo CER:</span>
                    {{ Form::file('archivo_cer', ['class' => 'form-control']) }}
                </div>  
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Archivo KEY:</span>
                    {{ Form::file('archivo_key', ['class' => 'form-control']) }}
                </div>  
            </div>
        </div>
        <div class="spacer5" ></div>
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Contraseña Sello:</span>
                    {{ Form::password('sello_contrasenia', array('class' => 'form-control','disabled' => $disable)) }}
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
                    {!! Form::text('calle',$contribuyente->calle,array('class' => 'form-control','disabled' => $disable,'id'=>'calle','placeholder'=>'Calle')) !!}
                </div>  
            </div> 
        </div>
        <div class="spacer5" ></div>
        <div class="row">
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Nro Ext:</span>
                    {!! Form::text('num_ext',$contribuyente->num_ext,array('class' => 'form-control','disabled' => $disable,'id'=>'num_ext','placeholder'=>'Numero Exterior')) !!}
                </div>  
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">Nro Int:</span>
                    {!! Form::text('num_int',$contribuyente->num_int,array('class' => 'form-control','disabled' => $disable,'id'=>'num_int','placeholder'=>'Numero Interior')) !!}
                </div>  
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">Localidad:</span>
                    {!! Form::text('localidad',$contribuyente->localidad,array('class' => 'form-control','disabled' => $disable,'id'=>'localidad','placeholder'=>'Localidad')) !!}
                </div>  
            </div> 
        </div>
        <div class="spacer5" ></div>
        <div class="row">
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Pais:</span>
                    {!! Form::select('pais', (['0' => '--Selecciona--'] + $listPais->toArray()), $contribuyente->idPais, ['class' => 'form-control','disabled' => $disable,'id'=>'idPais']) !!}
                </div>  
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Estado:</span>
                    <div id="caja_estado" >
                        {!! Form::text('estado',$contribuyente->estado,array('class' => 'form-control','disabled' => $disable,'id'=>'estado','placeholder'=>'Estado')) !!}
                    </div>
                </div>  
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Municipio:</span>
                    <div id="caja_municipio" >
                        {!! Form::text('municipio',$contribuyente->municipio,array('class' => 'form-control','disabled' => $disable,'id'=>'municipio','placeholder'=>'Municipio')) !!}
                    </div>
                </div>  
            </div> 
        </div>
        <div class="spacer5" ></div>
        <div class="row">
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Colonia:</span>
                    <div id="caja_colonia" >
                        {!! Form::text('colonia',$contribuyente->colonia,array('class' => 'form-control','disabled' => $disable,'id'=>'colonia','placeholder'=>'Colonia')) !!}
                    </div>
                </div>  
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Codigo Postal:</span>
                    {!! Form::text('codigo_postal',$contribuyente->codigo_postal,array('class' => 'form-control','disabled' => $disable,'id'=>'codigo_postal','placeholder'=>'Codigo Postal')) !!}
                </div>  
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
                    <span class="input-group-addon">Correo Electronico:</span>
                    {!! Form::text('correo_electronico',$contribuyente->correo_electronico,array('class' => 'form-control','disabled' => $disable,'placeholder'=>'E-Mail')) !!}
                </div>  
            </div>
        </div>    
        <div class="row">    
            <div class="col-sm-12">
                <div class="input-group">
                    <span class="input-group-addon">Celular a 10 Digitos:</span>
                    {!! Form::number('cel',$contribuyente->celular,['maxlength'=>'10','min'=>'1','disabled' => $disable,'class' => 'form-control','placeholder'=>'Movil']) !!}
                </div>  
            </div>
        </div>
    </div>

</div> 
@if(!$disable)  
<div class="panel panel-success">
    <!--div class="panel-heading">Notificaciones al Receptor</div-->
    <div class="panel-body">
        <div class="row">    
            <div class="col-sm-12">
                <div class="pull-right">
                    @isset($nuevo)
                    {!! Form::submit('Registrar',['class' => 'btn btn-info']) !!}
                    @else
                    {!! Form::submit('Actualizar',['class' => 'btn btn-info']) !!}  
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
@endif

<script>
    jQuery(document).ready(function ($) {
        cambioRegimenFiscal("{{$contribuyente->tipo_regimen}}");
        selectedRegimeFiscal("{{$contribuyente->regimen_fiscal}}");
        convertUpperCase('rfc');
        convertUpperCase('razon_social');
        convertUpperCase('calle');
        convertUpperCase('num_ext');
        convertUpperCase('num_int');
        convertUpperCase('localidad');
        convertUpperCase('estado');
        convertUpperCase('municipio');
        convertUpperCase('colonia');
        convertUpperCase('codigo_postal');
    });
    function selectedRegimeFiscal(idRegFiscal) {
        $("#regimen_fiscal > option").each(function () {
            if (this.value == idRegFiscal) {
                $(this).prop('selected', true);
            }
        });
    }
    function convertUpperCase(id) {
        $('#' + id).keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });
    }

    function cambioRegimenFiscal(value) {
        $.ajax({
            url: "{{ url('/administracion/obtenerRegimenFiscal') }}",
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}'
            },
            method: 'GET',
            dataType: 'json',
            async: false,
            data: {'tipo_regimen': value},
            success: function (data) {
                var id = 'regimen_fiscal';
                var select = $('#' + id);
                select.children('option:not(:first)').remove();
                if (data.length > 0) {
                    addComboArray(id, data);
                }/*else{
                 $("#select2-cmbMunicipio-container").html("--Selecciona--");
                 }*/
            }
        });

    }

    function addComboArray(idDiv, values) {
        for (var x in values) {
            var txt = '<option value="' + values[x].id + '">' + values[x].nombre + '</option>';
            $("#" + idDiv).append(txt);
        }
    }

</script>    