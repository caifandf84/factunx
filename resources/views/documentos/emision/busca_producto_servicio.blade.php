<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<link href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css" rel='stylesheet' type='text/css'>    
<link href="https://www.conuxi.com/jqGrid_JS_5.0.2/css/ui.jqgrid.css" rel='stylesheet' type='text/css'>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/i18n/grid.locale-es.js"></script>
<script src="https://www.conuxi.com/jqGrid_JS_5.0.2/js/jquery.jqGrid.min.js"></script> 

<div class="panel panel-primary">
      <div class="panel-heading">Lista de Productos-Servicios</div>
      <div class="panel-body">
            <div class="table table-responsive">  
                <table id="grid-prodserv"></table>
                <div id="grid-page-prodserv"></div>
            </div>
      </div>
</div>

<script>
    $(document).ready(function () {
        jQuery("#grid-prodserv").jqGrid({
            url:"{{ url('/productoServicio/listaGrid') }}",
            datatype: "json",
            height: 300,
            colNames: ['Id','Accion', 'Id', 'Nombre'],
            colModel: [
                {name: 'bpsId', index: 'bpsId', width: 80, sortable: false,hidden:true},
                {name: 'bpsAccion', index: 'bpsAccion', width: 80},
                {name: 'bpsIdSat', index: 'bpsIdSat', width: 90},
                {name: 'bpsNombre', index: 'bpsNombre', width: 650}
            ],
            rowNum: 10,
            rowList: [10, 20, 30],
            sortname: 'id',
            sortorder: "desc",
            viewrecords: true,
            pager: '#grid-page-prodserv',
        });
        jQuery("#grid-prodserv").jqGrid('navGrid', "#grid-page-prodserv", {edit: false, add: false, del: false,search: false});
        jQuery("#grid-prodserv").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    });
</script>
