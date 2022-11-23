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
                <table id="grid-b-conceptos"></table>
                <div id="grid-page-b-conceptos"></div>
            </div>
        </div>

<script type="text/javascript" >
jQuery("#grid-b-conceptos").jqGrid({
	url:"{{ url('/concepto/listaGrid') }}",
        datatype: "json",
        height: 400,
   	colNames:['Acción','Código','Nombre','IdUnidad', 'Unidad','Prod/Servicio', 'Precio Unitario','No Identificación','Predial'],
   	colModel:[
                {name:'bAccion',index:'bAccion', width:90},
                {name:'bCodigo',index:'bCodigo', width:100},
   		{name:'bNombre',index:'bNombre', width:220},
   		{name:'bidUnidad',index:'bidUnidad', width:90,hidden:true},
   		{name:'bUnidad',index:'bUnidad', width:150},
                {name:'bProdcutoServicio',index:'bProdcutoServicio', width:80},
                {name:'bPrecioUnitario',index:'bPrecioUnitario', width:110,sorttype:"float",formatter: 'number', formatoptions: { decimalPlaces: 2 }},
   		{name:'bNoIdentificacion',index:'bNoIdentificacion', width:90},
   		{name:'bPredial',index:'bPredial', width:90, align:"right"}
                
   	],
        rowNum:10,
   	rowList:[10,20,30],
        viewrecords: true,
        sortname: 'bCodigo',
        sortorder: "desc",
   	pager: '#grid-page-b-conceptos',
});
jQuery("#grid-b-conceptos").jqGrid('navGrid',"#grid-page-b-conceptos",{edit:false,add:false,del:false,search:false});
jQuery("#grid-b-conceptos").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

</script>