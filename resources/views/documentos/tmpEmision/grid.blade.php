<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<link href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css" rel='stylesheet' type='text/css'>    
<link href="https://www.conuxi.com/jqGrid_JS_5.0.2/css/ui.jqgrid.css" rel='stylesheet' type='text/css'>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/i18n/grid.locale-es.js"></script>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/jquery.jqGrid.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<link href="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script> 
<style>
    .ui-jqgrid {
        font-family: Arial;
    }
    .ui-jqgrid > .ui-jqgrid-view {
        font-size: 12px;
    }
    .ui-jqgrid .ui-jqgrid-hdiv .ui-jqgrid-labels .ui-th-column {
        color: blue;
    }
    .ui-dialog.alert-dialog {
        font-size: 1em;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-body">
        <div class="table table-responsive">  
            <table id="grid-tmp-documentos"></table>
            <div id="grid-tmp-page-documentos"></div>
        </div>
    </div>
</div>
<form id="formDocumentoTmp" action="{{ url('/documentos/ver/continuar/tmpEmision') }}" method="get" >
    <input type="hidden" value="1" id="idDocumento" name="idDocumento" >
</form>
<script>
$(document).ready(function () {
    var url="{{ url('/documentos/tmp/listado') }}";
    jQuery("#grid-tmp-documentos").jqGrid({
        url: "{{ url('/documentos/tmp/listado') }}",
        datatype: "json",
        height: 300,
        colNames: ['Acción', 'Id', 'idTipoDoc', 'Documento', 'Serie', 'Número', 'Fecha','Monto'],
        colModel: [
            {name: 'accion', index: 'accion', width: 120},
            {name: 'id', index: 'id', width: 100},
            {name: 'idTipoDocumento', index: 'idTipoDocumento', width: 100, hidden: true},
            {name: 'nomTipoDocumento', index: 'nomTipoDocumento', width: 200},
            {name: 'serie', index: 'serie', width: 100},
            {name: 'numero', index: 'numero', width: 100},
            {name: 'fecha', index: 'fecha', width: 150},
            {name: 'monto', index: 'monto', width: 150, sorttype: "float", formatter: 'number', formatoptions: {decimalPlaces: 2}}
        ],
        rowNum: 10,
        rowList: [10, 20, 30],
        sortname: 'fecha',
        sortorder: "desc",
        viewrecords: true,
        pager: '#grid-tmp-page-documentos',
        caption: "Documentos para Emitir"
    });
    jQuery("#grid-tmp-documentos").jqGrid('navGrid', "#grid-tmp-page-documentos", {edit: false, add: false, del: false});
});
function seleccionaDocumento(value) {
    $("#idDocumento").val(value);
    $("#formDocumentoTmp").submit();
}
</script>
