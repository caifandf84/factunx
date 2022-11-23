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
      <div class="panel-heading">Lista de Documentos para Cancelar</div>
      <div class="panel-body">
            <div class="table table-responsive">  
                    <table id="grid-documentos"></table>
                    <div id="grid-page-documentos"></div>
            </div>
      </div>
</div>
<form id="formDocumentoCancelar" action="{{ url('/documentos/cancelacion/cancrelarPorId') }}" method="get" >
        <input type="hidden" value="1" id="idDocumento" name="idDocumento" >
</form>
<script>
    $(document).ready(function () {
        jQuery("#grid-documentos").jqGrid({
            url:"{{ url('/documentos/cancelacion/listado') }}",
            datatype: "json",
            height: 300,
            colNames: ['Acción','Id', 'idTipoDoc', 'Documento', 'Serie', 'Número', 'Fecha', 'RFC Emisor', 'Nombre Emisor',
                        'RFC Receptor', 'Nombre Receptor','Moneda', 'Monto','Estatus','UUID'],
            colModel: [
                {name: 'accion', index: 'accion', width: 90},
                {name: 'id', index: 'id', width: 60},
                {name: 'idTipoDocumento', index: 'idTipoDocumento', width: 50,hidden:true},
                {name: 'nomTipoDocumento', index: 'nomTipoDocumento', width: 150},
                {name: 'serie', index: 'serie', width: 60},
                {name: 'numero', index: 'numero', width: 60},
                {name: 'fecha', index: 'fecha', width: 120},
                {name: 'rfcEmisor', index: 'rfcEmisor', width: 110,hidden:true},
                {name: 'nomEmisor', index: 'nomEmisor', width: 200,hidden:true},
                {name: 'rfcReceptor', index: 'rfcReceptor', width: 130},
                {name: 'nomReceptor', index: 'nomReceptor', width: 220},
                {name: 'moneda', index: 'moneda', width: 60},
                {name: 'monto', index: 'monto', width: 90, sorttype: "float", formatter: 'number', formatoptions: {decimalPlaces: 2}},
                {name: 'estatus', index: 'estatus', width: 60},
                {name: 'uuid', index: 'uuid', width: 280}
            ],
            rowNum: 10,
            rowList: [10, 20, 30],
            sortname: 'fecha',
            sortorder: "desc",
            viewrecords: true,
            pager: '#grid-page-documentos',
            caption: "Documentos para cancelar ante el SAT"
        });
        jQuery("#grid-documentos").jqGrid('navGrid', "#grid-page-documentos", {edit: false, add: false, del: false});
    });
    function cancelaDocumento(value){
        if(confirm("Desea cancelar documento?")){
            $("#idDocumento").val(value);
            $("#formDocumentoCancelar").submit();
        }
    }
</script>
