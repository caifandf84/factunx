<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://www.conuxi.com/factunx_frameworks/js/app.js"></script>
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
    .ui-jqgrid .ui-jqgrid-htable th div { height: auto; }
    .ui-jqgrid .ui-jqgrid-htable th { height: auto; }
</style>
<!--selecciona tipo de documentos-->
<div class="container">
    {{ Form::open(array('url' => '/','method' => 'post')) }}    
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
            </div>
        </div>    
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon">Correo Electr&oacute;nico {{$docNombre}}:</span>
                            {!! Form::text('correo_electronico',$doc->email,array('class' => 'form-control','placeholder'=>'E-Mail')) !!}
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
                                    <div class="panel panel-primary">
                                            <div class="panel-heading">Lista de Conribuyentes</div>
                                            <div class="panel-body">
                                                  <div class="table table-responsive">  
                                                      <table id="grid-contribuyentes"></table>
                                                      <div id="grid-page-contribuyentes"></div>
                                                  </div>
                                            </div>
                                      </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <div class="input-group">
                            <span class="input-group-addon">Motivo Relaci&oacute;n UUID:</span>
                            {!! Form::select('tipo_relacion', (['0' => '--Selecciona--'] + $tipoRelaciones->toArray()), $doc->tipoRelacion, ['class' => 'form-control','id'=>'tipo_relacion']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">UUID Relacionado:</span>
                            {!! Form::text('uuid_relacionado',$doc->uuidRelacionado,array('class' => 'form-control','id'=>'uuid_relacionado','placeholder'=>'Uuid Relacionado')) !!}
                        </div>  
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="panel-group">
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
                            {!! Form::select('forma_pago', (['0' => '--Selecciona--'] + $listFormaPago->toArray()), $doc->idFormaPago, ['class' => 'form-control']) !!}
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
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Condiciones de Pago:</span>
                            {!! Form::textarea('condiciones_pago', $doc->condicionesPago, ['size' => '40x2']) !!}
                        </div>  
                    </div>
                </div>
            </div>
        </div>
            <div class="panel-group">
        <div class="panel panel-success">
            <div class="panel-heading">Conceptos del Documento </div>
            <div class="panel-body">
                <h4>Sección para agregar los conceptos al documeto </h4>   
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Codigo:</span>
                                <div class="row">
                                    <div class="col-sm-11">
                                        {!! Form::text('codigo',null,array('id'=>'codigo','class' => 'form-control','placeholder'=>'Codigo','onblur'=>'seleccionaComplete(this.value);')) !!}
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" data-toggle="modal" data-target="#buscaConceptos" class="btn btn-info pull-right">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="buscaConceptos" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                      @include('documentos.emision.conceptoTotales.buscar_conceptos')
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
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Codigo de Barras:</span>
                            {!! Form::text('codigo_barras',null,array('id'=>'codigo_barras','class' => 'form-control','onblur'=>'seleccionaCodigoBarras(this.value);')) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Cantidad:</span>
                            {!! Form::number('cantidad', null, ['id'=>'cantidad','min'=>'1','class' => 'form-control','placeholder' => 'Cantidad']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Unidad:</span>
                            {!! Form::select('unidad', (['0' => '--Selecciona--']+ $listUnidad->toArray()), null, ['id'=>'unidad','class' => 'form-control']) !!}
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Nombre:</span>
                            {!! Form::textarea('nombre', null, ['id'=>'nombre','class' => 'form-control','rows'=>'2']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Precio Unit.:</span>
                            {!! Form::number('precio_unit',null,['id'=>'precio_unit','min'=>'1','class' => 'form-control','placeholder' => 'Precio']) !!}
                        </div>  
                    </div>
                </div> 
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">*Producto Servicio:</span>
                                <div class="row">
                                    <div class="col-sm-11">
                                        {!! Form::text('producto_servicio',null,array('id'=>'producto_servicio','class' => 'form-control','placeholder'=>'Producto Servicio')) !!}
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" data-toggle="modal" data-target="#buscaProductoServicio" class="btn btn-info pull-right">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="buscaProductoServicio" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                      @include('documentos.emision.busca_producto_servicio')
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
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Cantidad Descuento:</span>
                            {!! Form::number('descuento', null, ['id'=>'descuento','min'=>'1','class' => 'form-control','placeholder'=>'Descuento']) !!}
                        </div>  
                    </div>
                    
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Nro. Identificacion:</span>
                            {!! Form::text('num_dentificacion',null,array('id'=>'num_dentificacion','class' => 'form-control','placeholder'=>'Identificacion')) !!}
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span class="input-group-addon">Predial:</span>
                            {!! Form::text('predial',null,array('id'=>'predial','class' => 'form-control','placeholder'=>'Predial')) !!}
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tr>
                                <td class="info">
                                    <p>Sugerencia para saber que <strong>producto o servicio</strong> pertencen sus conceptos
                                    <a href="https://www.sat.gob.mx/informacion_fiscal/factura_electronica/Paginas/criterios_catalogo_productos_servicios.aspx" 
                                       target="_blank"
                                       class="fa fa-fw fa-edit" >Sugerencia SAT</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::button('Borrar',['class' => 'btn btn-info','onClick' => 'borrarConcepto();']) !!}  
                    </div>
                    <div class="col-sm-6">
                        {!! Form::button('Agregar',['id'=>'btn_add_concepto','class' => 'btn btn-info','onClick' => 'agregarConcepto();']) !!}
                    </div>
                </div> 
                <hr/>
                {!! Form::hidden('idModConcepto', '0',array('id' => 'idModConcepto')) !!}
                <h4>Lista de conceptos finales en documento </h4> 
                @include('documentos.emision.conceptoTotales.conceptos')
            </div> 
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">Impuesto</div>
            <div class="panel-body">
                <h4> Tipo de impuesto </h4>   
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Tipo:</span>
                            {!! Form::text('tipo_impuesto', 'IVA', ['readonly' => 'true','class' => 'form-control']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon alert-danger">%Tasa:</span>
                            {!! Form::number('porc_tasa_imp','16',['id'=>'porc_tasa_imp','class' => 'form-control','placeholder' => 'Tasa','onblur' => 'cambioTotales();']) !!}
                        </div>  
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Importe Impuesto:</span>
                            {!! Form::text('importe_imp',null,array('id'=>'importe_imp','class' => 'form-control','readonly' => 'true')) !!}
                        </div>  
                    </div>
                </div>
            </div>
        </div>      
        <div class="panel panel-success">
            <div class="panel-heading">Totales</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="info">
                                <td><p>Descuento:</p></td>
                                <td>
                                    {!! Form::number('descuento_tot', null, ['id'=>'descuento_tot','min'=>'1','class' => 'form-control','readonly' => 'true','onblur' => 'cambioTotales();']) !!}</td>
                                <td colspan="6" ></td>
                                <!--td><p>Desc. Descuento:</p></td>
                                <td colspan="5" >{!! Form::text('desc_descuento_tot',null,array('id'=>'desc_descuento_tot','class' => 'form-control')) !!}</td>
                                -->
                            </tr>
                            <tr class="success">
                                <td><p>Otros Imp. Ret:</p></td>
                                <td>{!! Form::text('otro_imp_ret_tot',null,array('id'=>'otro_imp_ret_tot','class' => 'form-control','readonly' => 'true')) !!}</td>
                                <td><p>Otros Imp. Tras:</p></td>
                                <td>{!! Form::text('otro_imp_tras_tot',null,array('id'=>'otro_imp_tras_tot','class' => 'form-control','readonly' => 'true')) !!}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="info">
                                <td><p>Total Imp. Ret:</p></td>
                                <td>{!! Form::text('total_imp_ret_tot',null,array('id'=>'total_imp_ret_tot','class' => 'form-control','readonly' => 'true')) !!}</td>
                                <td><p>Total Imp. Tras:</p></td>
                                <td>{!! Form::text('total_imp_tras_tot',null,array('id'=>'total_imp_tras_tot','class' => 'form-control','readonly' => 'true')) !!}</td>
                                <td><p>SubTotal::</p></td>
                                <td>{!! Form::text('sub_total_tot',null,array('id'=>'sub_total_tot','class' => 'form-control','readonly' => 'true')) !!}</td>
                                <td><p>Total:</p></td>
                                <td>{!! Form::text('total_tot',null,array('id'=>'total_tot','class' => 'form-control','readonly' => 'true')) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> 
        </div>
    </div> 
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-6">
                    <div class="pull-right">
                        {!! Form::submit('Timbrar',['class' => 'btn btn-info']) !!}
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
        }
        var id = "#btn_tipo_doc" + idDoc;
        $(id).click();
        asignaTipoDoc(idDoc);
    }
        jQuery("#grid-contribuyentes").jqGrid({
            url:"{{ url('/contribuyente/listaGrid') }}",
            datatype: "json",
            height: 200,
            colNames: ['Id','Accion', 'RFC', 'Razón Social', 'Calle', 'Num Ext', 'Estado', 'Delg/Municipio', 'Colonia',
                        'Correo','numInt','localidad','codigoPostal','idColonia','idMunicipio','idEstado','pais',
                        'idPais','celular'],
            colModel: [
                {name: 'id', index: 'id', width: 80, sortable: false,hidden:true},
                {name: 'accion', index: 'accion', width: 80},
                {name: 'bRfc', index: 'bRfc', width: 90},
                {name: 'bRazonSocial', index: 'bRazonSocial', width: 190},
                {name: 'bCalle', index: 'bCalle', width: 150},
                {name: 'bNumExt', index: 'bNumExt', width: 60},
                {name: 'bEstado', index: 'bEstado', width: 110},
                {name: 'bMunicipio', index: 'bMunicipio', width: 120},
                {name: 'bColonia', index: 'bColonia', width: 90},
                {name: 'bCorreo', index: 'bCorreo', width: 170},
                {name: 'bNumInt', index: 'bNumInt', width: 170,hidden:true},
                {name: 'bLocalidad', index: 'bLocalidad', width: 170,hidden:true},
                {name: 'bCodigoPostal', index: 'bCodigoPostal', width: 170,hidden:true},
                {name: 'bIdColonia', index: 'bIdColonia', width: 170,hidden:true},
                {name: 'bIdMunicipio', index: 'bIdMunicipio', width: 170,hidden:true},
                {name: 'bIdEstado', index: 'bIdEstado', width: 170,hidden:true},
                {name: 'bPais', index: 'bPais', width: 170,hidden:true},
                {name: 'bIdPais', index: 'bIdPais', width: 170,hidden:true},
                {name: 'bCelular', index: 'bCelular', width: 170,hidden:true}
            ],
            rowNum: 10,
            rowList: [10, 20, 30],
            sortname: 'id',
            sortorder: "desc",
            viewrecords: true,
            pager: '#grid-page-contribuyentes',
        });
        jQuery("#grid-contribuyentes").jqGrid('navGrid', "#grid-page-contribuyentes", {edit: false, add: false, del: false,search: false});
        jQuery("#grid-contribuyentes").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    $('#unidad').select2();
        onlyInteger("cantidad");
        $("#codigo").autocomplete({
            source: "{{ url('/concepto/autocmplete') }}",
            minLength: 3,
            select: function (event, ui) {
                console.log(ui.item); 
                $('#codigo').val(ui.item.id);
                seleccionaComplete(ui.item.id);
                return false;
            }
        });
    $('select').select2();    
});

function cambioTipoComprobante() {

    var metodoPago = $("#metodo_pago").val();
    var tipoDoc = $("#tipoDoc").val();
    if (metodoPago == "PUE" && tipoDoc == 33) {
        cambioComboTComprobante("I");
    } else if (metodoPago == "PPD" && tipoDoc == 33) {
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
function limpiarContribuyente(){
    $("[name='rfc']").val('');
    $("[name='razon_social']").val('');
    $("[name='codigo_postal']").val('');
    $("[name='cel']").val('');
    $("[name='correo_electronico']").val('');
}

    function seleccionaComplete(id){
        $.ajax( {
          url: "{{ url('/concepto/obtenerPorId') }}",
          dataType: "json",
          data: {id:id},
          success: function( data ) {
            console.log(data);  
            changeConcepto(data);
          }
      });
    }
    
    function seleccionaCodigoBarras(codigoBarras){
        var value=$("#codigo_barras").val();
        $.ajax( {
          url: "{{ url('/concepto/obtenerPorCodigoBarras') }}",
          dataType: "json",
          data: {codigoBarras:codigoBarras},
          success: function( data ) {
            console.log(data);  
            changeConcepto(data);
          }
      });
    }
    
    function seleccConcepto(value){
        var rowData = $("#grid-b-conceptos").getRowData(value);
        var concepto={nombre:rowData.bNombre,
                        precio_unitario:rowData.bPrecioUnitario,
                        identificacion:rowData.bNoIdentificacion,
                        predial:rowData.bPredial,
                        id_unidad:rowData.bidUnidad,
                        id_prod_servicio:rowData.bProdcutoServicio
                    };
        $('#codigo').val(rowData.bCodigo);            
        changeConcepto(concepto);            
        $("#buscaConceptos .close").click();
    }
    function seleccProductoServicio(value){
        var rowData = $("#grid-prodserv").getRowData(value);
        $('#producto_servicio').val(rowData.bpsIdSat);
        $("#buscaProductoServicio .close").click();
    }
    /**Conceptos**/
    function changeConcepto(concepto){
        $("#nombre").val(concepto.nombre);
        $("#precio_unit").val(concepto.precio_unitario);
        $("#num_dentificacion").val(concepto.identificacion);
        $("#predial").val(concepto.predial);
        $("#unidad > option").each(function() {
            if(this.value==concepto.id_unidad){
                //$("#unidad").val(this.value);
                $(this).prop('selected', true); 
                $("#select2-unidad-container").html(this.text); 
                $("[name='unidad']").val(this.text);
            }
        });
        $("#producto_servicio").val(concepto.id_prod_servicio);
        $("#unidad").val(concepto.id_unidad);
    }
    /*****************validaciones*********/

    function terminarProceso(value) {
        var values = $("#grid-conceptos").getRowData();
        var txtResponse = "";
        var idTipoImpuesto = 2;
        var impuestoTasa = $("#porc_tasa_imp").val();
        if (values == '') {
            alert("Se requiere agregar conceptos e impuestos");
            return false;
        }
        for (var x in values) {
            var idConcepto = "conceptos_id_"+x;
            var idImp = "impuestos_id_"+x;
            var tasa =  impuestoTasa / 100;
            var importeDesc = (Number(values[x].importe) - Number(values[x].descuento));
            var impuestoTotal= (Number(tasa) * Number(importeDesc));
            var stringData = values[x].noConcepto + "|" + values[x].codigo + "|" + values[x].producto + "|"+ values[x].prodServicio + "|"
                    + values[x].noIdentificacion + "|" + values[x].cantidad + "|" + values[x].idUnidad + "|" + values[x].unidad + "|"
                    + values[x].predial + "|" + values[x].precio + "|" + values[x].importe+"|" + values[x].descuento;
            //alert(JSON.stringify(jsonData));
            txtResponse += '<input id="' + idConcepto + '" value="' + stringData + '" name="conceptos[]" type="hidden">';
            var stringImp = values[x].noConcepto + "|" + values[x].producto + "|" + idTipoImpuesto + "|" + "Retencion" + "|" + 4 + "|" + "IVA" + "|"
                + impuestoTasa + "|" + truncateDecimals(impuestoTotal,2);
            txtResponse += '<input id="' + idImp + '" value="' + stringImp + '" name="impuestos[]" type="hidden">';    
        }
        $("#resultado_grids").html(txtResponse);
        $("#resultado_grids").val(txtResponse);
        return confirm("En este momento sera timbrado ante el SAT\n Desea continuar?");
    }



    function onlyInteger(idDiv) {
        var id = "#" + idDiv;
        $(id).keydown(function (event) {
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9
                    || event.keyCode == 27 || event.keyCode == 13
                    || (event.keyCode == 65 && event.ctrlKey === true)
                    || (event.keyCode >= 35 && event.keyCode <= 39)) {
                return;
            } else {
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                    event.preventDefault();
                }
            }
        });
    }
    function calculoTasa() {
        var rowData = $("#grid-conceptos").getRowData();
        var tasa = $("#porc_tasa_imp").val();
        tasa = truncateDecimals(tasa, 2);
        var importeTasa = 0;
        var importe = 0;
        var importeDecimal = 0.00;
        for (x in rowData) {
            importeTasa = Number(rowData[x].importe) - Number(rowData[x].descuento);
            //importeTasa = Number(importeTasa) - Number(rowData[x].descuento);
            importeTasa = truncateDecimals(importeTasa, 2);
            var importeTmp = (importeTasa * (tasa/100));
            importeTmp = truncateDecimals(importeTmp, 2);
            console.log("importeTmp"+importeTmp);
            importe = importeTmp + importe;
            importeDecimal = importe.toFixed(2);
            console.log("importe"+importeDecimal);
        }
        console.log("importe"+importeDecimal);
        $("#importe_imp").val(importeDecimal);
    }

</script>

