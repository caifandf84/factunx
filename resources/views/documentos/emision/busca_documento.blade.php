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

<div class="panel panel-primary">
      <div class="panel-heading">Lista de Documentos</div>
      <div class="panel-body">
            <div class="table table-responsive">  
                <table id="grid-documentos"></table>
                <div id="grid-page-documentos"></div>
            </div>
      </div>
</div>

<script type="text/javascript" >
    $(document).ready(function () {
        jQuery("#grid-documentos").jqGrid({
            url:"{{ url('/documentos/listaGrid') }}",
            datatype: "json",
            height: 300,
            colNames: ['Id','Acc','Uuid', 'idTipoDoc', 'Documento', 'Serie', 'Número', 'Fecha', 'RFC Emisor', 'Nombre Emisor',
                        'RFC Receptor', 'Nombre Receptor','Moneda', 'Monto','Estatus','PDF','XML','Enviar'],
            colModel: [
                {name: 'id', index: 'id', width: 60,hidden:true},
                {name: 'sel', index: 'sel', width: 40},
                {name: 'uuid', index: 'uuid', width: 230},
                {name: 'idTipoDocumento', index: 'idTipoDocumento', width: 50,hidden:true},
                {name: 'nomTipoDocumento', index: 'nomTipoDocumento', width: 150},
                {name: 'serie', index: 'serie', width: 60},
                {name: 'numero', index: 'numero', width: 60},
                {name: 'fecha', index: 'fecha', width: 120},
                {name: 'rfcEmisor', index: 'rfcEmisor', width: 110,hidden:true},
                {name: 'nomEmisor', index: 'nomEmisor', width: 200,hidden:true},
                {name: 'rfcReceptor', index: 'rfcReceptor', width: 110},
                {name: 'nomReceptor', index: 'nomReceptor', width: 220},
                {name: 'moneda', index: 'moneda', width: 60},
                {name: 'monto', index: 'monto', width: 50, sorttype: "float", formatter: 'number', formatoptions: {decimalPlaces: 2}},
                {name: 'estatus', index: 'estatus', width: 60,hidden:true},
                {name: 'pdf', index: 'pdf', width: 40,hidden:true},
                {name: 'xml', index: 'xml', width: 40,hidden:true},
                {name: 'email', index: 'email', width: 40,hidden:true}
            ],
            rowNum: 10,
            rowList: [10, 20, 30],
            sortname: 'fecha',
            sortorder: "desc",
            viewrecords: true,
            pager: '#grid-page-documentos',
            caption: "Documentos SAT"
        });
        jQuery("#grid-documentos").jqGrid('navGrid', "#grid-page-documentos", {edit: false, add: false, del: false,search: false});
        jQuery("#grid-documentos").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    });
</script>

