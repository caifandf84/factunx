@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-success">
        <div class="panel-body">
            <div class="row">
                    <ul class="list-group" >
                        @foreach($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger" >{{ $error }}</li>
                        @endforeach
                    </ul>
              </div>
            <h2>Contribuyente</h2>
            <p>Si es nuevo contribuyente agregar datos, en caso de TIMBRAR con contribuyente existenete</p>
            <p>seleccionar contribuyente para su aprobaci&oacute;n.</p>
            <ul class="nav nav-pills">
                <li class="active"><a data-toggle="pill" href="#nuevo">Nuevo Contribuyente</a></li>
                <li><a data-toggle="pill" href="#asignar">Seleccionar Contribuyente</a></li>
            </ul>
            <div class="tab-content">
                <div id="nuevo" class="tab-pane fade in active">
                    <h3>Nuevo Contribuyente</h3>
                    @include('administracion.datosContribuyente.formulario',[
                            'disable' => false,
                            'nuevo' => true,
                            'listPais'=>(new \App\Pais())->getComboByPais(),
                            'listEstado'=>(new\App\ Estado())->getComboByPais(1),
                            'contribuyente' => new \App\Contribuyente()
                        ])
                </div>
                <div id="asignar" class="tab-pane fade">
                    <h3>Asignar a Contribuyente</h3>
                    <h4>Este contribuyente sera el Emisor de Facturas, Recibos, ETC ante el SAT</h4>
                    <h4>Una vez que sea aceptado por el contribuyente emisor por correo electronico su asignaci&oacute;n</h4>
                    <div class="panel-heading">Selecciona Contribuyente</div>
                    <div class="panel panel-success">
                        <div class="panel-body">
                            @include('documentos.emision.busca_contribuyente')
                            {{ Form::open(array('url' => '/registrar/contribuyente/seleccionado','method' => 'post')) }}  
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon alert-danger">*R.F.C.:</span>
                                        {!! Form::text('rfc',null,array('class' => 'form-control','placeholder'=>'Registro Federal del Contribuyente','readonly' => 'true')) !!}
                                    </div>  
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon alert-danger">*Razón Social:</span>
                                        {!! Form::text('razon_social',null,array('class' => 'form-control','placeholder'=>'Razon Social','readonly' => 'true')) !!}
                                    </div>  
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon alert-danger">*Correo:</span>
                                        {!! Form::text('correo',null,array('class' => 'form-control','placeholder'=>'Registro Federal del Contribuyente','readonly' => 'true')) !!}
                                    </div>  
                                </div>
                                <div class="col-sm-6">
                                    {{ Form::hidden('id', '-1') }}
                                    {{ Form::hidden('tipo_registro', 'asignacion') }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        {!! Form::submit('Finalizar',['class' => 'btn btn-info']) !!}
                                    </div> 
                                </div> 
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function seleccContribuyente(value) {
        var rowData = $("#grid-contribuyentes").getRowData(value);
        console.log(rowData);
        $("[name='rfc']").val(rowData.bRfc);
        $("[name='razon_social']").val(rowData.bRazonSocial);
        $("[name='correo']").val(rowData.bCorreo);
        $("[name='id']").val(rowData.id);
    }
</script>
@endsection
