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
      <div class="panel-heading">Lista de Usuarios</div>
      <div class="panel-body">
            <div class="table table-responsive">  
                    <table id="grid-usuarios"></table>
                    <div id="grid-page-usuarios"></div>
            </div>
      </div>
</div>
<script>
    $(document).ready(function () {
        jQuery("#grid-usuarios").jqGrid({
            url:"{{ url('/contribuyente/listaUsuariosGrid') }}",
            datatype: "json",
            height: 300,
            colNames: ['Id', 'Nombre', 'Correo', 'Fecha de Creacion'],
            colModel: [
                {name: 'id', index: 'id', width: 60,hidden:true},
                {name: 'name', index: 'name', width: 200,align: "center"},
                {name: 'email', index: 'email', width: 250,align: "center"},
                {name: 'created_at', index: 'created_at', width: 200,sorttype: "date"}
            ],
            rowNum: 10,
            rowList: [10, 20, 30],
            sortname: 'id',
            sortorder: "desc",
            viewrecords: true,
            pager: '#grid-page-usuarios',
            caption: "Usuarios"
        });
        jQuery("#grid-usuarios").jqGrid('navGrid', "#grid-page-usuarios", {edit: false, add: false, del: false});
    });
</script>
