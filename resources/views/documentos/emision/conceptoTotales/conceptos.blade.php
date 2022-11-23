<!DOCTYPE html>
<!--
Alta, Baja y Cambio de Grid Conceptos
-->
<link href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css" rel='stylesheet' type='text/css'>    
<link href="https://www.conuxi.com/jqGrid_JS_5.0.2/css/ui.jqgrid.css" rel='stylesheet' type='text/css'>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/i18n/grid.locale-es.js"></script>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/jquery.jqGrid.min.js"></script> 


        <div class="row" align="center" >
            <div class="table table-responsive">  
                <table id="grid-conceptos"></table>
                <div id="grid-page-conceptos"></div>
            </div>
        </div>

<script type="text/javascript" >
jQuery("#grid-conceptos").jqGrid({
	datatype: "local",
        height: 250,
   	colNames:['Acción','Nro Concepto','Código', 'Producto','IdProdServicio', 'Identificación','Desc','Cantidad','IdUnidad','Unidad','Predial','Precio','Importe'],
   	colModel:[
                {name:'act',index:'act', width:120,sortable:false},
   		{name:'noConcepto',index:'noConcepto', width:110,sortable:false},
   		{name:'codigo',index:'codigo', width:80,sortable:false},
   		{name:'producto',index:'producto', width:220,sortable:false},
                {name:'prodServicio',index:'prodServicio', width:90, align:"right",sortable:false},
                {name:'noIdentificacion',index:'noIdentificacion', width:80,sortable:false},
   		{name:'descuento',index:'descuento', width:50, align:"right",sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 2 },sortable:false},
                {name:'cantidad',index:'cantidad', width:70, align:"right",sorttype:"float",sortable:false},
   		{name:'idUnidad',index:'idUnidad', width:80, align:"right",sortable:false,hidden:true},
                {name:'unidad',index:'unidad', width:70, align:"right",sortable:false},		
   		{name:'predial',index:'predial', width:80,align:"right",sorttype:"float",sortable:false},		
   		{name:'precio',index:'precio', width:80,sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 2 },sortable:false},
                {name:'importe',index:'importe', width:80,sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 5 },sortable:false}
   	],
        rowNum:10,
   	rowList:[10,20,30],
        viewrecords: true,
   	pager: '#grid-page-conceptos',
   	caption: "Conceptos asignados al documento"
});
jQuery("#grid-conceptos").jqGrid('navGrid',"#grid-page-conceptos",{edit:false,add:false,del:false,search:false});

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
        var descuento = $("#descuento").val();
        var precio = $("#precio_unit").val();
        var productoServicio = $("#producto_servicio").val();
        var idMod = $("#idModConcepto").val();
        if(unidad=='0'){
            alert("Se requiere Seleccionar Unidad en Concepto");
            return;
        }
        if(cantidad == '' || (parseInt(cantidad))<0){
            alert("Se requiere Cantidad mayor a cero en Concepto");
            return;
        }
        if(productoServicio == ''){
            alert("Ser requiere Seleccionar Producto-Servicio en Concepto");
            return;
        }
        if(producto.length>= 500){
            alert("El Nombre debe ser menor a 500 caracteres");
            return;
        }
        if(idMod != '0'){
            modificaConceptoFinal(idMod)
        }else{
            var cantTrun=truncateDecimals(cantidad,5);
            var precioTrun=truncateDecimals(precio,5);
            //var importe=(cantTrun * precioTrun);
            var importe = (Math.round(((precioTrun*cantTrun) *100))/100);
            importe=truncateDecimals(importe,5);
            descuento = truncateDecimals(descuento,2);
            if(Number(descuento)>Number(importe)){
                alert("Descuento debe ser menor a importe");
                return;
            }
            var data={act:el+mod,noConcepto:i,codigo:codigo,producto:producto,prodServicio:productoServicio,noIdentificacion:noIdentificacion,cantidad:cantidad,
                idUnidad:unidad,unidad:txtUnidad,predial:predial,descuento:descuento,precio:precio,importe:importe};
            $("#grid-conceptos").jqGrid('addRowData',i,data);
            addComboValue("no_concept_imp",i,i);
            addComboValue("no_concept_ped",i,i);
            i++;
        }
        limpiarFieldsConcepto();
        cambioTotales();
}

function borrarConcepto(){
    limpiarFieldsConcepto();
}
function modificaConceptoFinal(idRow){
        var codigo = $("#codigo").val();
        var cantidad = $("#cantidad").val();
        var producto = $("#nombre").val();
        var noIdentificacion = $("#num_dentificacion").val();
        var txtUnidad = $("#unidad option:selected").text();
        var unidad = $("#unidad").val();
        var predial = $("#predial").val();
        var descuento = $("#descuento").val();
        var precio = $("#precio_unit").val();
        var productoServicio = $("#producto_servicio").val();
        var cantTrun=truncateDecimals(cantidad,5);
        var precioTrun=truncateDecimals(precio,5);
        var importe=(cantTrun * precioTrun);
        importe=truncateDecimals(importe,5);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'codigo', codigo);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'cantidad', cantidad);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'producto', producto);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'prodServicio', productoServicio);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'noIdentificacion', noIdentificacion);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'idUnidad', unidad);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'unidad', txtUnidad);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'predial', predial);
        jQuery("#grid-conceptos").jqGrid('setCell', idRow, 'descuento', descuento);
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
        $("#descuento").val('');
        $("#producto_servicio").val('');
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
        if(this.value==rowData.idUnidad){
            $(this).prop('selected', true); 
            $("#select2-unidad-container").html(this.text) 
        }
    });
    $("#predial").val(rowData.predial);
    $("#precio_unit").val(rowData.precio); 
    $("#descuento").val(rowData.descuento); 
    $("#producto_servicio").val(rowData.prodServicio);
    $("#idModConcepto").val(rowData.noConcepto);
}

function truncateDecimals (num, digits) {
    var numS = num.toString(),
        decPos = numS.indexOf('.'),
        substrLength = decPos == -1 ? numS.length : 1 + decPos + digits,
        trimmedResult = numS.substr(0, substrLength),
        finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;

    return parseFloat(finalResult);
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

function stringToDate(value){
        var values=value.split(" ");
        var stringDate = values[0].split("/");     
        var formatDate= stringDate[2]+"-"+stringDate[1]+"-"+stringDate[0]+"T"+
                        values[1]+":00";     
        d = new Date(formatDate);    
        return d;
}

function dateToString(value){
        var values=value.split(" ");
        var stringDate = values[0].split("-"); 
        var stringTime = values[1].split(":"); 
        return stringDate[0]+"/"+stringDate[1]+"/"+stringDate[2]+" "+stringTime[0]+":"+stringTime[1];
}

function cambioTotales(){
    calculoTasa();
    var values = $('#grid-conceptos').jqGrid('getGridParam','data');
    var total=0;var subTotal=0;var descuentoGrid=0;var totalImpRet=0;var totalOtroImpRet=0;var totalImpTras=0;var totalOtroImpTras=0;
    for (x in values) {
        subTotal = subTotal+values[x].importe;
        if(!isNaN(values[x].descuento)){
            descuentoGrid=Number(descuentoGrid)+Number(values[x].descuento);
        }
        //subTotal = subTotal - descuentoGrid;
    }
    $("#descuento_tot").val(descuentoGrid);
    totalImpTras=$("#importe_imp").val(); 
    var idDoc = "{{$doc->idTipoDoc}}"
    if(idDoc==71){
        totalImpRet = $("#importe_imp_iva_ret").val();
        totalImpRet = Number(totalImpRet) + Number($("#importe_isr_ret").val());
    }
    var tTotalImpTras=truncateDecimals(totalImpTras,5);
    var tTotalOtroImpTras = truncateDecimals(totalOtroImpTras,5);
    var tTotalOtroImpRet= truncateDecimals(totalOtroImpRet,5);
    var tTotalImpRet = truncateDecimals(totalImpRet,5);
    var tSubTotal= truncateDecimals(subTotal,5);
    $('#total_imp_tras_tot').val(tTotalImpTras);
    $('#otro_imp_tras_tot').val(tTotalOtroImpTras);
    $('#otro_imp_ret_tot').val(tTotalOtroImpRet);
    $('#total_imp_ret_tot').val(tTotalImpRet);
    $('#sub_total_tot').val(tSubTotal);
    var resTotales = (Number(tSubTotal) - Number(tTotalImpRet) - Number(tTotalOtroImpRet));
    resTotales = (resTotales - Number(descuentoGrid));
    resTotales = truncateDecimals(Number(resTotales),5);
    var sumTotales = (Number(tTotalImpTras) + Number(tTotalOtroImpTras));
    sumTotales = truncateDecimals(Number(sumTotales),5);
    total = resTotales + sumTotales;
    total = total.toFixed(5);
    /*total = (Number(tSubTotal) - Number(tTotalImpRet) - Number(tTotalOtroImpRet) -Number(descuentoGrid) 
            + Number(tTotalImpTras) + Number(tTotalOtroImpTras));*/
    $('#total_tot').val((Number(total)).toFixed(2));
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
