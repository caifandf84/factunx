@extends('layouts.app')
@section('content')

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
   
<div class="container">
    {{ Form::open(array('url' => 'documentos/emision/generar','method' => 'post','onsubmit' => 'return terminarProceso(this,"2");')) }}    
    <div class="row">
        <div class="col-sm-8">
            <h2>Conceptos y Totales</h2>
            <p>Se requiere agregar conceptos como productos, servicios, honorarios, etc</p>
            <p>Que se requiera mandar al SAT</p>
        </div>
        <div class="col-sm-4">
            <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                     aria-valuemin="0" aria-valuemax="100" style="width:80%">
                    80% Completo
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
    <div class="row">
        <!-- Modal -->
        <div class="modal fade" id="pdfPervioModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body" id="bodyPrevioModal" >  
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
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
                            {!! Form::number('porc_tasa_imp','16',['id'=>'porc_tasa_imp','min'=>'10','max'=>'20','step'=>'any','class' => 'form-control','placeholder' => 'Tasa','onblur' => 'cambioTotales();']) !!}
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
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-12"><div id="resultado_grids" ></div></div>
                <div class="col-sm-6">
                    {!! Form::button('Atras',['class' => 'btn btn-info','onClick' => 'regresar();']) !!}  
                </div>
                <div class="col-sm-6">
                    <div class="pull-right">
                        {!! Form::button('Vista Previa',['class' => 'btn btn-info',
                                                    'data-toggle'=>'modal','data-target'=>'#pdfPervioModal',
                                                    'onClick' => 'cargarPrevio();']) !!} 
                        {!! Form::submit('Finalizar',['class' => 'btn btn-info']) !!}
                    </div> 
                </div> 
            </div>
        </div>
    </div>    
    {{ Form::close() }}
</div>
<script type="text/javascript" >
    $(document).ready(function () {
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
        cargarConceptosPrevios();
    });
    
    function regresar(){
        $(document).ready(function(){
            $('<form action="{{url('documentos/emision/datosGeneral')}}" method="post" >\n\
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" /></form>').appendTo('body').submit();
        });
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
    var previoConceptos=[];
    var pervioImpuestos=[];
    function terminarProceso(value,idIsPrevio) {
        var values = $("#grid-conceptos").getRowData();
        var txtResponse = "";
        var idTipoImpuesto = 2;
        var impuestoTasa = $("#porc_tasa_imp").val();
        pervioImpuestos=[];
        previoConceptos=[];
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
            var scapeData=escapeJavaScriptText(stringData);
            previoConceptos.push(scapeData);
            txtResponse += '<input id="' + idConcepto + '" value="' + scapeData + '" name="conceptos[]" type="hidden">';
            var stringImp = values[x].noConcepto + "|" + values[x].producto + "|" + idTipoImpuesto + "|" + "Retencion" + "|" + 2 + "|" + "IVA" + "|"
                + impuestoTasa + "|" + impuestoTotal;
            var scapeImp=escapeJavaScriptText(stringImp);  
            pervioImpuestos.push(scapeImp);
            txtResponse += '<input id="' + idImp + '" value="' + scapeImp + '" name="impuestos[]" type="hidden">';    
        }
        $("#resultado_grids").html(txtResponse);
        $("#resultado_grids").val(txtResponse);
        if(idIsPrevio==="2"){
            return confirm("En este momento sera timbrado ante el SAT\n Desea continuar?");
        }else{
            return true;
        }
    }

    function escapeJavaScriptText($string) 
    { 
        return  $string.replace('"','&quot;');
        //return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\"))); 
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
            importeTasa = importeTasa.toFixed(2); //truncateDecimals(importeTasa, 2);
            var importeTmp = (importeTasa * (tasa/100));
            //importeTmp = truncateDecimals(importeTmp, 2);
            importeTmp = importeTmp.toFixed(2);
            console.log("importeTmp"+importeTmp);
            //importe =  parseFloat(importeTmp) +  parseFloat(importe);
            importe = (new Number(importe)) + (new Number(importeTmp));
            importe = importe.toFixed(2);
            importeDecimal = truncateDecimals(parseFloat(importe),2);
            console.log("importe"+importeDecimal);
        }
        console.log("importe total"+importeDecimal);
        $("#importe_imp").val(importeDecimal);
        /*
        console.log("importeTasa"+importeTasa);
        importeTasa = truncateDecimals(importeTasa, 2);
        console.log("importeTasa"+importeTasa);
        
        var importe = (importeTasa * (tasa/100));
        console.log("importe"+importe);
        importe = truncateDecimals(importe, 2);
        console.log("importe"+importe);
        $("#importe_imp").val(importe);*/
    }
function cargarPrevio(){
        terminarProceso(null,"1");
        var descuento_tot = $("#descuento_tot").val();	
        var desc_descuento_tot = $("#desc_descuento_tot").val();
        var otro_imp_ret_tot = $("#otro_imp_ret_tot").val();
        var otro_imp_tras_tot = $("#otro_imp_tras_tot").val();
        var total_imp_ret_tot = $("#total_imp_ret_tot").val();
        var total_imp_tras_tot = $("#total_imp_tras_tot").val();
        var sub_total_tot = $("#sub_total_tot").val();
        var total_tot = $("#total_tot").val();
        var pedimentos = [];
        var porc_tasa_imp = $("#porc_tasa_imp").val();
        $.ajax({
            url: "{{ url('/documentos/verPrevio') }}",
            headers: {
                'X-CSRF-Token':'{{ csrf_token() }}'
            },
            data: {'descuento_tot': descuento_tot,
		'desc_descuento_tot': desc_descuento_tot,
		'otro_imp_ret_tot': otro_imp_ret_tot,
		'otro_imp_tras_tot': otro_imp_tras_tot,
		'total_imp_ret_tot': total_imp_ret_tot,
		'total_imp_tras_tot': total_imp_tras_tot,
		'sub_total_tot': sub_total_tot,
		'total_tot': total_tot,
		'conceptos': previoConceptos,
		'impuestos': pervioImpuestos,
		'pedimentos': pedimentos,
		'porc_tasa_imp': porc_tasa_imp},
            method: 'POST',
            dataType: 'json',
            async: false,
            success: function(data){
                $("#bodyPrevioModal").html('');
                $("#bodyPrevioModal").html('<object type="application/pdf" data="data:application/pdf;base64,'+data+'" width="100%" height="500" style="height: 85vh;">No Support</object>');
            }
          });
}
function cargarConceptosPrevios(){
    @isset($doc->conceptos)
         <?php  
            $lengthConceptos = count($doc->conceptos);
            for($x = 0; $x < $lengthConceptos; $x++) {
                $concepto=$doc->conceptos[$x];
                if(is_array($concepto)){
                    $c=new \App\Pojo\Concepto();
                    $c->id = $concepto['id'];
                    $c->codigo = $concepto['codigo'];
                    $c->nombre = $concepto['nombre'];
                    $c->identificacion = $concepto['identificacion'];
                    $c->cantidad = $concepto['cantidad'];
                    $c->idUnidad = $concepto['idUnidad'];
                    $c->unidadNom = $concepto['unidadNom'];
                    $c->predial = $concepto['predial'];
                    $c->precioUnitario = $concepto['precioUnitario'];
                    $c->importe = $concepto['importe'];
                    $c->descuento = $concepto['descuento'];
                    $c->idSatProductoServicio = $concepto['idSatProductoServicio'];
                    $concepto = $c;
                }else if(!is_object($concepto)) {
                    $conceptoArray = explode("|", $doc->conceptos[$x]);
                    if(count($conceptoArray)==12){
                        $c=new \App\Pojo\Concepto();
                        $c->id = $conceptoArray[0];
                        $c->codigo = $conceptoArray[1];
                        $c->nombre = $conceptoArray[2];
                        $c->idSatProductoServicio = $conceptoArray[3];
                        $c->identificacion = $conceptoArray[4];
                        $c->cantidad = $conceptoArray[5];
                        $c->idUnidad = $conceptoArray[6];
                        $c->unidadNom = $conceptoArray[7];
                        $c->predial = $conceptoArray[8];
                        $c->precioUnitario = $conceptoArray[9];
                        $c->importe = $conceptoArray[10];
                        $c->descuento = $conceptoArray[11];
                        $concepto = $c;
                    }
                }
                ?> 
                var cantidad="<?php echo($concepto->cantidad!=null?$concepto->cantidad:'') ?>";
                $("#cantidad").val(cantidad);
                var nombre="<?php echo($concepto->nombre!=null?$concepto->nombre:'') ?>";
                $("#nombre").val(nombre);
                var codigo="<?php echo($concepto->codigo!=null?$concepto->codigo:'') ?>";
                $("#codigo").val(codigo);
                var noIdentificacion="<?php echo($concepto->identificacion!=null?$concepto->identificacion:'') ?>";
                $("#noIdentificacion").val(noIdentificacion);
                var unidad="<?php echo($concepto->idUnidad!=null?$concepto->idUnidad:'')?>";
                $("#unidad").val(unidad);
                $("#unidad > option").each(function() {
                    if(this.value==unidad){
                        $(this).prop('selected', true); 
                        $("#select2-unidad-container").html(this.text); 
                        $("[name='unidad']").val(this.text);
                    }
                });
                //$("#unidad").trigger('change');
                var predial="<?php echo(($concepto->predial!=null && $concepto->predial!=='null')?$concepto->predial:'') ?>";
                $("#predial").val(predial);
                var descuento="<?php echo(($concepto->descuento!=null && $concepto->descuento!='null')?$concepto->descuento:'') ?>";
                $("#descuento").val(descuento);
                var precio_unit="<?php echo($concepto->precioUnitario!=null?$concepto->precioUnitario:'') ?>";
                $("#precio_unit").val(precio_unit);
                var producto_servicio="<?php echo($concepto->idSatProductoServicio!=null?$concepto->idSatProductoServicio:'') ?>";
                $("#producto_servicio").val(producto_servicio);
                $("#idModConcepto").val(0);
                $("#unidad").val(unidad);
                agregarConcepto();
        <?php                
            }
         ?>        
    @endisset  
}
</script>
@endsection
