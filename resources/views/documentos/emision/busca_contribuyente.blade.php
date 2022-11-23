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
      <div class="panel-heading">Lista de Conribuyentes</div>
      <div class="panel-body">
            <div class="table table-responsive">  
                <table id="grid-contribuyentes"></table>
                <div id="grid-page-contribuyentes"></div>
            </div>
      </div>
</div>

<script type="text/javascript" >
    $(document).ready(function () {
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
    });
</script>
