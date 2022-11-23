@extends('layouts.app')
@section('content')

<link href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css" rel='stylesheet' type='text/css'>    
<link href="https://www.conuxi.com/jqGrid_JS_5.0.2/css/ui.jqgrid.css" rel='stylesheet' type='text/css'>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/i18n/grid.locale-es.js"></script>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/jquery.jqGrid.min.js"></script> 
<link href="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/mascoota_files/select2-4.0.2/select2-4.0.2/dist/js/select2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<link href="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script>

<!--style>
    .ui-jqgrid tr.jqgrow td {font-size:2.5em}
</style-->
<div class="container">
{{ Form::open(array('url' => 'documentos/emision/generar','method' => 'post','onsubmit' => 'return terminarProceso(this);')) }}    
  <div class="row">
      <div class="col-sm-8">
            <h2>Conpetos y Totales</h2>
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
<div class="panel-group">

    <div class="panel panel-success">
          <div class="panel-heading">Conceptos del Documento </div>
          <div class="panel-body">
           <h4>Sección para agregar los conceptos al documeto </h4>   
           <div class="row">
              <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon">Codigo:</span>
                    {!! Form::text('codigo',null,array('id'=>'codigo','class' => 'form-control','placeholder'=>'Codigo')) !!}
                </div>  
              </div>
              <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon">Codigo de Barras:</span>
                    {!! Form::text('codigo_barras',null,array('id'=>'codigo_barras','class' => 'form-control')) !!}
                </div>
              </div>
              </div> 
              <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Cantidad:</span>
                    {!! Form::number('cantidad', null, ['id'=>'cantidad','min'=>'1','class' => 'form-control','placeholder' => 'Cantidad']) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon alert-danger">*Unidad:</span>
                    {!! Form::select('unidad', (['0' => '--Selecciona--']+ $listUnidad->toArray()), null, ['id'=>'unidad','class' => 'form-control']) !!}
                </div>  
              </div>
           </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon alert-danger">*Nombre:</span>
                        {!! Form::text('nombre',null,array('id'=>'nombre','class' => 'form-control','placeholder'=>'Nombre')) !!}
                    </div>  
                </div>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon alert-danger">*Precio Unit.:</span>
                        {!! Form::number('precio_unit',null,['id'=>'precio_unit','min'=>'1','class' => 'form-control','placeholder' => 'Precio']) !!}
                    </div>  
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon">Nro. Identificacion:</span>
                        {!! Form::text('num_dentificacion',null,array('id'=>'num_dentificacion','class' => 'form-control','placeholder'=>'Identificacion')) !!}
                    </div>  
                </div>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon">Predial:</span>
                        {!! Form::text('predial',null,array('id'=>'predial','class' => 'form-control','placeholder'=>'Predial')) !!}
                    </div>  
                </div>
            </div>
           <div class="row">
               <br>
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
            <div class="row" align="center" >
                <div class="table table-responsive">  
                    <table id="grid-conceptos"></table>
                    <div id="grid-page-conceptos"></div>
                </div>
            </div>
          </div> 
    </div>

    <div class="panel panel-success">
          <div class="panel-heading">Impuestos</div>
          <div class="panel-body">
              <h4>Sección para agregar algún tipo de impuesto si existe</h4>   
                <div class="row">
                   <div class="col-sm-4">
                     <div class="input-group">
                         <span class="input-group-addon alert-danger">Nro Concept:</span>
                         {!! Form::select('no_concept_imp', (['0' => '--Todos--']), null, ['id'=>'no_concept_imp','class' => 'form-control']) !!}
                     </div>  
                   </div>
                   <div class="col-sm-4">
                     <div class="input-group">
                         <span class="input-group-addon alert-danger">*Tipo de Impuesto:</span>
                         {!! Form::select('tipo_imp', (['0' => '--Selecciona--']+ $listtipoImpuesto->toArray()), null, ['id'=>'tipo_imp','class' => 'form-control','onchange' => 'cambioImpuesto(this.value);']) !!}
                     </div>  
                   </div>
                   <div class="col-sm-4">
                     <div class="input-group">
                         <span class="input-group-addon alert-danger">*Impuesto:</span>
                            <div id="caja_cmb_imp" >
                            {!! Form::select('cmbImp', (['0' => '--Selecciona--']), null, ['class' => 'form-control','id'=>'cmbImp']) !!}
                            </div>
                            <div id="caja_imp" >
                            {!! Form::text('imp',null,['class' => 'form-control','id'=>'imp']) !!}
                            </div>
                     </div>  
                   </div>
                </div>
                <div class="row">
                   <div class="col-sm-4">
                     <div class="input-group">
                         <span class="input-group-addon alert-danger">%Tasa:</span>
                         {!! Form::number('porc_tasa_imp',null,['id'=>'porc_tasa_imp','class' => 'form-control','placeholder' => 'Tasa','onblur' => 'calculoTasa();']) !!}
                     </div>  
                   </div>
                   <div class="col-sm-4">
                     <div class="input-group">
                         <span class="input-group-addon">Importe Impuesto:</span>
                         {!! Form::text('importe_imp',null,array('id'=>'importe_imp','class' => 'form-control','readonly' => 'true')) !!}
                     </div>  
                   </div>
                </div>  
                <div class="row">
                    <br/>
                </div>
                 <div class="row">
                     <div class="col-sm-6">
                         {!! Form::button('Borrar',['class' => 'btn btn-info','onClick' => 'borrarImpuesto();']) !!}  
                     </div>
                     <div class="col-sm-6">
                         {!! Form::button('Agregar',['id'=>'btn_add_concepto','class' => 'btn btn-info','onClick' => 'agregarImpuesto();']) !!}
                     </div>
                 </div> 
                 <hr/>
                {!! Form::hidden('idModImpuesto', '0',array('id' => 'idModImpuesto')) !!}
                <h4>Lista de impuestos del Documento </h4> 
                <div class="row" align="center" >
                    <div class="table table-responsive">  
                        <table id="grid-impuestos"></table>
                        <div id="grid-page-impuestos"></div>
                    </div>
                </div>
              </div> 
    </div>
    <div class="panel panel-success">
          <div class="panel-heading">Pedimentos</div>
          <div class="panel-body">
              <h4>Sección para agregar los pedimentos aduanales al documento </h4>   
           <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                         <span class="input-group-addon">Nro Concept:</span>
                         {!! Form::select('no_concept_ped', (['0' => '--Seleccionar--']), null, ['id'=>'no_concept_ped','class' => 'form-control']) !!}
                     </div>   
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">*Numero:</span>
                    {!! Form::number('numero_ped', null, ['id'=>'numero_ped','min'=>'1','class' => 'form-control']) !!}
                </div>  
              </div>
              <div class="col-sm-4">
                <div class="input-group">
                    <span class="input-group-addon">*Fecha:</span>
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control" id="fecha_ped" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div> 
                </div>  
              </div>
           </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon">*Aduana:</span>
                        {!! Form::text('aduana_ped',null,array('id'=>'aduana_ped','class' => 'form-control','placeholder'=>'Aduana')) !!}
                    </div>  
                </div>
            </div> 
             <div class="row">
                     <div class="col-sm-6">
                         {!! Form::button('Borrar',['class' => 'btn btn-info','onClick' => 'borrarPedimento();']) !!}  
                     </div>
                     <div class="col-sm-6">
                         {!! Form::button('Agregar',['id'=>'btn_add_concepto','class' => 'btn btn-info','onClick' => 'agregarPedimento();']) !!}
                     </div>
                 </div> 
                 <hr/>
                {!! Form::hidden('idModPedimento', '0',array('id' => 'idModPedimento')) !!}
                <h4>Lista de pedimentos del Documento </h4> 
                <div class="row" align="center" >
                    <div class="table table-responsive">  
                        <table id="grid-pedimentos"></table>
                        <div id="grid-page-pedimentos"></div>
                    </div>
                </div>  
          </div> 
    </div>
    <div class="panel panel-success">
          <div class="panel-heading">Totales</div>
          <div class="panel-body">
              <div class="table-responsive"> 
                  <table class="table">
                      <tbody>
                          <tr class="info">
                              <td><p>Descuento:</p></td>
                              <td>
                                  {!! Form::number('descuento_tot', null, ['id'=>'descuento_tot','min'=>'1','class' => 'form-control','onblur' => 'cambioTotales();']) !!}</td>
                              <td><p>Desc. Descuento:</p></td>
                              <td colspan="5" >{!! Form::text('desc_descuento_tot',null,array('id'=>'desc_descuento_tot','class' => 'form-control')) !!}</td>
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
                {{ Form::open(array('url' => 'documentos/emision/datosGeneral','method' => 'post')) }} 
                        {!! Form::submit('Atras',['class' => 'btn btn-info']) !!}
                {{ Form::close() }}
                <!--a href="{{url('/documentos/emision/datosGeneral')}}" 
                   class="btn btn-info">Atras</a-->
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
<script>
$(document).ready(function() {
    $('select').select2(); 
    $("#caja_cmb_imp").hide();
    $("#caja_imp").show();
    onlyInteger("cantidad");
    $('#datetimepicker1').datetimepicker({locale:'es'});
});
jQuery("#grid-pedimentos").jqGrid({
	datatype: "local",
        height: 250,
   	colNames:['Acción','Nro Concepto','Producto','Numero','Fecha','Aduana'],
   	colModel:[
                {name:'act',index:'act', width:120,sortable:false},
   		{name:'noConcepto',index:'noConcepto', width:80},
                {name:'producto',index:'producto', width:280},
   		{name:'numero',index:'numero', width:100,sorttype:"float",formatter: 'number'},
                {name:'fecha',index:'fecha', width:110,formatter:'date', formatoptions: {srcformat : 'Y-m-d H:i:s',newformat : 'd-m-Y H:i:s'}},
   		{name:'aduana',index:'aduana', width:150}
   	],
        rowNum:10,
   	rowList:[10,20,30],
        viewrecords: true,
   	pager: '#grid-page-pedimentos',
   	caption: "Pedimentos asignados al documento"
});
jQuery("#grid-pedimentos").jqGrid('navGrid',"#grid-page-pedimentos",{edit:false,add:false,del:false});

jQuery("#grid-impuestos").jqGrid({
	datatype: "local",
        height: 250,
   	colNames:['Acción','Nro Concepto','Producto','Id Tipo Impuesto','Tipo Impuesto','Id Impuesto' ,'Impuesto', 'Tasa','Importe'],
   	colModel:[
                {name:'act',index:'act', width:120,sortable:false},
   		{name:'noConcepto',index:'noConcepto', width:80},
                {name:'producto',index:'producto', width:280},
   		{name:'idTipoImpuesto',index:'idTipoImpuesto',hidden:true, width:50},
   		{name:'nomTipoImpuesto',index:'nomTipoImpuesto', width:100},
                {name:'idImpuesto',index:'idImpuesto',hidden:true, width:80},
   		{name:'nomImpuesto',index:'nomImpuesto', width:100, align:"right"},
   		{name:'tasa',index:'tasa', width:90, align:"right",sorttype:"float"},		
   		{name:'importe',index:'importe', width:90,sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 2 }}		
   	],
        rowNum:10,
   	rowList:[10,20,30],
        viewrecords: true,
   	pager: '#grid-page-impuestos',
   	caption: "Impuestos asignados al documento"
});
jQuery("#grid-impuestos").jqGrid('navGrid',"#grid-page-impuestos",{edit:false,add:false,del:false});

jQuery("#grid-conceptos").jqGrid({
	datatype: "local",
        height: 250,
   	colNames:['Acción','Nro Concepto','Código', 'Producto', 'Nro Identificación','Cantidad','IdUnidad','Unidad','Predial','Precio','Importe'],
   	colModel:[
                {name:'act',index:'act', width:120,sortable:false},
   		{name:'noConcepto',index:'noConcepto', width:120,sortable:false},
   		{name:'codigo',index:'codigo', width:90,sortable:false},
   		{name:'producto',index:'producto', width:220,sortable:false},
                {name:'noIdentificacion',index:'noIdentificacion', width:110,sortable:false},
   		{name:'cantidad',index:'cantidad', width:90, align:"right",sorttype:"float",sortable:false},
   		{name:'idUnidad',index:'idUnidad', width:90, align:"right",sortable:false,hidden:true},
                {name:'unidad',index:'unidad', width:90, align:"right",sortable:false},		
   		{name:'predial',index:'predial', width:90,align:"right",sorttype:"float",sortable:false},		
   		{name:'precio',index:'precio', width:90,sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 2 },sortable:false},
                {name:'importe',index:'importe', width:90,sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 2 },sortable:false}
   	],
        rowNum:10,
   	rowList:[10,20,30],
        viewrecords: true,
   	pager: '#grid-page-conceptos',
   	caption: "Conceptos asignados al documento"
});
jQuery("#grid-conceptos").jqGrid('navGrid',"#grid-page-conceptos",{edit:false,add:false,del:false});

var i=1;
function agregarConcepto(){
        el = "<input style='height:22px;width:50px;' type='button' value='Elimnar' onclick=\"eliminarConcepto('"+i+"');\"  />"; 
        mod = "<input style='height:22px;width:55px;' type='button' value='Modificar' onclick=\"modificarConcepto('"+i+"');\"  />"; 
        var codigo = $("#codigo").val();
        var cantidad = $("#cantidad").val();
        var producto = $("#nombre").val();
        var noIdentificacion = $("#num_dentificacion").val();
        var unidad = $("#unidad").val();
        var txtUnidad = $("#unidad option:selected").text();
        var predial = $("#predial").val();
        var precio = $("#precio_unit").val();
        var idMod = $("#idModConcepto").val();
        if(unidad=='0'){
            alert("Ser equiere Seleccionar Unidad en Concepto");
            return;
        }
        if(cantidad == '' || (parseInt(cantidad))<0){
            alert("Ser equiere Cantidad mayor a cero en Concepto");
            return;
        }
        if(idMod != '0'){
            modificaConceptoFinal(idMod)
        }else{
            var importe=(Math.floor(cantidad) * Math.floor(precio));
            var data={act:el+mod,noConcepto:i,codigo:codigo,producto:producto,noIdentificacion:noIdentificacion,cantidad:cantidad,
                idUnidad:unidad,unidad:txtUnidad,predial:predial,precio:precio,importe:importe};
            $("#grid-conceptos").jqGrid('addRowData',i,data);
            addComboValue("no_concept_imp",i,i);
            addComboValue("no_concept_ped",i,i);
            i++;
        }
        limpiarFieldsConcepto();
        cambioTotales();
	//jQuery("#grid-conceptos").jqGrid('editGridRow',"new",{height:280,reloadAfterSubmit:false});
}

function borrarConcepto(){
    limpiarFieldsConcepto();
}
function modificaConceptoFinal(idRow){
        var codigo = $("#codigo").val();
        var cantidad = $("#cantidad").val();
        var producto = $("#nombre").val();
        var noIdentificacion = $("#num_dentificacion").val();
        var idUnidad = $("#unidad option:selected").text();
        var unidad = $("#unidad").val();
        var predial = $("#predial").val();
        var precio = $("#precio_unit").val();
        var importe=(Math.floor(cantidad) * Math.floor(precio));
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'codigo', codigo);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'cantidad', cantidad);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'producto', producto);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'noIdentificacion', noIdentificacion);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'idUnidad', idUnidad);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'unidad', unidad);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'predial', predial);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'precio', precio);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'importe', importe);
}
function limpiarFieldsConcepto(){
        $("#codigo").val('');
        $("#cantidad").val('');
        $("#nombre").val('');
        $("#num_dentificacion").val('');
        $("#predial").val('');
        $("#precio_unit").val('');
        $("#idModConcepto").val('0');
        $("#unidad > option").each(function() {
                if(this.value==0){
                    $(this).prop('selected', true); 
                    $("#select2-unidad-container").html(this.text) 
                }
        });
        $("#unidad").trigger('change');
}    
function eliminarConcepto(value){
    $("#grid-conceptos").jqGrid('delRowData',value);
    $("#grid-impuestos").jqGrid('delRowData',value);
    $("#grid-pedimentos").jqGrid('delRowData',value);
    $("#no_concept_ped > option").each(function() {
                if(this.value==value){
                    $(this).remove();
                }
        });
    $("#no_concept_imp > option").each(function() {
                if(this.value==value){
                    $(this).remove();
                }
        });    
}
function modificarConcepto(value){
    var rowData = $("#grid-conceptos").getRowData(value);
    $("#codigo").val(rowData.codigo);
    $("#cantidad").val(rowData.cantidad);
    $("#nombre").val(rowData.producto);
    $("#num_dentificacion").val(rowData.noIdentificacion);
    $("#unidad > option").each(function() {
        if(this.value==rowData.unidad){
            $(this).prop('selected', true); 
            $("#select2-unidad-container").html(this.text) 
        }
    });
    $("#predial").val(rowData.predial);
    $("#precio_unit").val(rowData.precio); 
    $("#idModConcepto").val(rowData.noConcepto);
}
///**********Impuestos*******************////
function agregarImpuesto(){
     var conceptoImp = $("#no_concept_imp").val();
    if(conceptoImp==0){
        var allConceptos = $('#grid-conceptos').jqGrid('getGridParam','data');
        for (var x in allConceptos) {
            var rowDataImpuesto = $("#grid-impuestos").getRowData(conceptoImp);
            if(rowDataImpuesto.noConcepto==undefined){
                agregarImporte(allConceptos[x].producto,allConceptos[x].noConcepto,allConceptos[x].precio);
            }
        }
        limpiarFieldsImpuestos();
        cambioTotales();
    }else{
        var rowDataImpuesto = $("#grid-impuestos").getRowData(conceptoImp);
        if(rowDataImpuesto.noConcepto==undefined){
            var rowData = $("#grid-conceptos").getRowData(conceptoImp);
            agregarImporte(rowData.producto,rowData.noConcepto,rowData.precio);
            limpiarFieldsImpuestos();
            cambioTotales();
        }
    }   
}
function calculoTasa(){  
   var conceptoImp = $("#no_concept_imp option:selected").val();
   if(conceptoImp!=0){
       var rowData = $("#grid-conceptos").getRowData(conceptoImp);
       var precio = rowData.precio;
       var tasa=$("#porc_tasa_imp").val();
       var importe = (precio/tasa);
       $("#importe_imp").val(importe);
   }else{
       alert("Se requiere seleccionar Concepto");
   }
   //
}

function agregarImporte(producto,idProducto,precio){
        el = "<input style='height:22px;width:50px;' type='button' value='Elimnar' onclick=\"eliminarImpuesto('"+idProducto+"');\"  />"; 
        mod = "<input style='height:22px;width:55px;' type='button' value='Modificar' onclick=\"modificarImpuesto('"+idProducto+"');\"  />"; 
        var idTipoImp,nomTipoImp,idImp,nomImp;
        
        idTipoImp=$( "#tipo_imp option:selected" ).val();
        nomTipoImp=$( "#tipo_imp option:selected" ).text();
        var tasa=$("#porc_tasa_imp").val();
        var importe = (precio/tasa);
        if(idTipoImp==3 || idTipoImp==4){
            nomImp=$("#imp").val();
            idImp=-1;
        }else{
            idImp=$( "#cmbImp option:selected" ).val();
            nomImp=$( "#cmbImp option:selected" ).text();
            
        }
        
        var data={act:el+mod,id:idProducto,noConcepto:idProducto,producto:producto,idTipoImpuesto:idTipoImp,nomTipoImpuesto:nomTipoImp,idImpuesto:idImp,nomImpuesto:nomImp,tasa:tasa,importe:importe};
        $("#grid-impuestos").jqGrid('addRowData',idProducto,data);
        //cambioTotales();
        
}
function borrarImpuesto(){
    limpiarFieldsImpuestos();
}
function eliminarImpuesto(value){
    $("#grid-impuestos").jqGrid('delRowData',value);
}
function modificarImpuesto(value){
    var rowData = $("#grid-impuestos").getRowData(value);
    $("#no_concept_imp > option").each(function() {
        if(this.value==rowData.noConcepto){
            $(this).prop('selected', true); 
            $("#select2-no_concept_imp-container").html(this.text) 
        }
    });
    $("#tipo_imp > option").each(function() {
        if(this.value==rowData.idTipoImpuesto){
            $(this).prop('selected', true); 
            $("#select2-tipo_imp-container").html(this.text) 
        }
    });
    if(rowData.idImpuesto != -1){
      $("#cmbImp > option").each(function() {
            if(this.value==rowData.idImpuesto){
                $(this).prop('selected', true); 
                $("#select2-cmbImp-container").html(this.text) 
            }
        });  
    }else{
        $("#imp").val(rowData.impuesto);
    }
    $("#porc_tasa_imp").val(rowData.tasa);
    $("#importe_imp").val(rowData.importe);
}

function limpiarFieldsImpuestos(){
        $("#porc_tasa_imp").val('');
        $("#importe_imp").val('');
        $("#imp").val('');
        
        $("#no_concept_imp > option").each(function() {
                if(this.value==0){
                    $(this).prop('selected', true); 
                    $("#select2-unidad-container").html(this.text) 
                }
        });
        $("#no_concept_imp").trigger('change');
        $("#tipo_imp > option").each(function() {
                if(this.value==0){
                    $(this).prop('selected', true); 
                    $("#select2-unidad-container").html(this.text) 
                }
        });
        $("#tipo_imp").trigger('change');
        $('#cmbImp').children('option:not(:first)').remove();
}  
/*****************Pedimentos*******************************/
function agregarPedimento(){
    var conceptoPed = $("#no_concept_ped").val();
    var el = "<input style='height:22px;width:50px;' type='button' value='Elimnar' onclick=\"eliminarPedimento('"+conceptoPed+"');\"  />"; 
    var mod = "<input style='height:22px;width:55px;' type='button' value='Modificar' onclick=\"modificarPedimento('"+conceptoPed+"');\"  />";     
    if(conceptoPed==0){
        alert("Se requiere seleccionar concepto en Pedimento");
    }else{
        var rowDataPedimento = $("#grid-pedimentos").getRowData(conceptoPed);
        if(rowDataPedimento.noConcepto==undefined){
            var numero = $("#numero_ped").val();
            var fecha = stringToDate($("#fecha_ped").val());
            var aduana = $("#aduana_ped").val();
            var rowData = $("#grid-conceptos").getRowData(conceptoPed);
            var data={act:el+mod,noConcepto:rowData.noConcepto,producto:rowData.producto,numero:numero,fecha:fecha,aduana:aduana};
            $("#grid-pedimentos").jqGrid('addRowData',rowData.noConcepto,data);
            limpiarFieldsPedimentos();
        }
    }   
}
function borrarPedimento(){
    limpiarFieldsPedimentos();
}
function eliminarPedimento(value){
    $("#grid-pedimentos").jqGrid('delRowData',value);
}
function modificarPedimento(value){
    var rowData = $("#grid-pedimentos").getRowData(value);
    $("#no_concept_ped > option").each(function() {
        if(this.value==rowData.noConcepto){
            $(this).prop('selected', true); 
            $("#select2-no_concept_ped-container").html(this.text) 
        }
    });
    $("#numero_ped").val(rowData.numero);
    $("#fecha_ped").val(dateToString(rowData.fecha));
    $("#aduana_ped").val(rowData.aduana);
}

function limpiarFieldsPedimentos(){
        $("#aduana_ped").val('');
        $("#numero_ped").val('');
        $("#fecha_ped").val('');
        $("#no_concept_ped > option").each(function() {
                if(this.value==0){
                    $(this).prop('selected', true); 
                    $("#select2-no_concept_ped-container").html(this.text); 
                }
        });
        $("#no_concept_ped").trigger('change');
}  

function addComboArray(idDiv, values) {
    for (var x in values) {
        var txt = '<option value="' + values[x].id + '">' + values[x].nombre + '</option>';
        $("#" + idDiv).append(txt);
    }
}

function addComboValue(id,nombre,value){
    var datas = [];
    var data ={id:value,nombre:nombre};
    datas.push(data);
    addComboArray(id, datas);
}

function cambioImpuesto(value) { 
    $.ajax({
            url: "{{ url('/documentos/emision/impuestoPorTipo') }}",
            headers: {
                'X-CSRF-Token':'{{ csrf_token() }}'
            },
            method: 'GET',
            dataType: 'json',
            data: {'tipo_impuesto': value},
            success: function(data){
                var id = 'cmbImp';
                var select = $('#'+id);
                select.children('option:not(:first)').remove();
                if (data.length > 0) {
                    addComboArray(id, data);
                    $("#caja_cmb_imp").show();
                    $("#caja_imp").hide();
                }else{
                    $("#caja_cmb_imp").hide();
                    $("#caja_imp").show();
                }
            }
          });
}
/*****************validaciones*********/

function terminarProceso(value){
    var values = $('#grid-conceptos').jqGrid('getGridParam','data');
    var valuesImp = $('#grid-impuestos').jqGrid('getGridParam','data');
    var valuesPed = $('#grid-pedimentos').jqGrid('getGridParam','data');
    var txtResponse = "";
    if(values=='' || valuesImp==''){
        alert("Se requiere agregar conceptos e impuestos");
        return false;
    }
    for (var x in values) {
        var idConcepto="conceptos_id_"+x;
        var stringData = values[x].noConcepto+"|"+values[x].codigo+"|"+values[x].producto+"|"
                         +values[x].noIdentificacion+"|"+values[x].cantidad+"|"+values[x].idUnidad+"|"+values[x].unidad+","
                         +values[x].predial+"|"+values[x].precio+"|"+values[x].importe;
        txtResponse+='<input id="'+idConcepto+'" value="'+stringData+'" name="conceptos[]" type="hidden">';
    }
    for (var y in valuesImp) {
        var idImp="impuestos_id_"+y;
        var stringImp = valuesImp[y].noConcepto+","+valuesImp[y].producto+","+valuesImp[y].idTipoImpuesto+","
                         +valuesImp[y].nomTipoImpuesto+","+valuesImp[y].idImpuesto+","+valuesImp[y].nomImpuesto+","
                         +valuesImp[y].tasa+","+valuesImp[y].importe;
        //alert(JSON.stringify(valuesImp[y]));
        txtResponse+='<input id="'+idImp+'" value="'+stringImp+'" name="impuestos[]" type="hidden">';
    }
    for (var z in valuesPed) {
        var idPed="pedimento_id_"+z;
        var stringPed = valuesPed[z].noConcepto+","+valuesPed[z].producto+","+valuesPed[z].numero+","
                         +valuesPed[z].fecha+","+valuesPed[z].aduana;
        txtResponse+='<input id="'+idPed+'" type="hidden" name="pedimentos[]" value="'+stringPed+'">';
    }
    $("#resultado_grids").html(txtResponse);
    return true;
}

function stringToDate(value){
        var values=value.split(" ");
        var stringDate = values[0].split("/");     
        var formatDate= stringDate[2]+"-"+stringDate[1]+"-"+stringDate[0]+"T"+
                        values[1]+":00";     
        d = new Date(formatDate);    
        return d;
}

function dateToString(value){
        "16-05-2017 05:36:00"
        var values=value.split(" ");
        var stringDate = values[0].split("-"); 
        var stringTime = values[1].split(":"); 
        return stringDate[0]+"/"+stringDate[1]+"/"+stringDate[2]+" "+stringTime[0]+":"+stringTime[1];
}

function cambioTotales(){
    var values = $('#grid-conceptos').jqGrid('getGridParam','data');
    var valuesImp = $('#grid-impuestos').jqGrid('getGridParam','data');
    var total=0;var subTotal=0;var totalImpRet=0;var totalOtroImpRet=0;var totalImpTras=0;var totalOtroImpTras=0;
    for (var x in values){ 
        subTotal=parseFloat(values[x].precio)+parseFloat(subTotal);
        //subTotal=floorFigure(values[x].precio)+subTotal;
    }
    for (var y in valuesImp) {
        //retencion se resta a subtotal
        if(valuesImp[y].idTipoImpuesto==1){
            totalImpRet=parseFloat(valuesImp[y].importe)+parseFloat(totalImpRet);
        }
        if(valuesImp[y].idTipoImpuesto==3){
            totalOtroImpRet=parseFloat(valuesImp[y].importe)+parseFloat(totalOtroImpRet);
        }
        if(valuesImp[y].idTipoImpuesto==2){
            totalImpTras=parseFloat(valuesImp[y].importe)+parseFloat(totalImpTras);
        }
        if(valuesImp[y].idTipoImpuesto==4){
            totalOtroImpTras=parseFloat(valuesImp[y].importe)+parseFloat(totalOtroImpTras);
        }
    }
    var descuento =$("#descuento_tot").val();
    descuento=parseFloat(descuento);
    $('#total_imp_tras_tot').val(floorFigure(totalImpTras));
    $('#otro_imp_tras_tot').val(floorFigure(totalOtroImpTras));
    $('#otro_imp_ret_tot').val(floorFigure(totalOtroImpRet));
    $('#total_imp_ret_tot').val(floorFigure(totalImpRet));
    $('#sub_total_tot').val(floorFigure(subTotal));
    total = (subTotal - totalImpRet - totalOtroImpRet + totalImpTras + totalOtroImpTras);
    if(descuento>0){
        var descuentoFinal = (total * descuento)/100; 
        total = total - descuentoFinal;
    }
    $('#total_tot').val(floorFigure(total));
}

function floorFigure(figure, decimals){
    if (!decimals) decimals = 2;
    var d = Math.pow(10,decimals);
    return (parseInt(figure*d)/d).toFixed(decimals);
}

function onlyInteger(idDiv){
    var id = "#"+idDiv; 
    $(id).keydown(function(event) {
                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 
                    || event.keyCode == 27 || event.keyCode == 13 
                    || (event.keyCode == 65 && event.ctrlKey === true) 
                    || (event.keyCode >= 35 && event.keyCode <= 39)){
                        return;
                }else {
                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                        event.preventDefault(); 
                    }   
                }
            });
}
</script>
</div>
@endsection