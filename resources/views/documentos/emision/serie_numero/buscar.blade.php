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
                <table id="grid-b-serie-numero"></table>
                <div id="grid-page-b-serie-numero"></div>
            </div>
        </div>

<script type="text/javascript" >
    //'{!!$seleccion!!}' '{!!$editar!!}' '{!!$eliminar!!}'
    var selecc='{!!$seleccion!!}';
    selecc=(selecc==1||selecc=='true'?true:false);
    var edit='{!!$editar!!}';
    edit=(edit==1||edit=='true'?true:false);
    var eliminar='{!!$eliminar!!}';
    eliminar=(eliminar==1||eliminar=='true'?true:false);
jQuery("#grid-b-serie-numero").jqGrid({
	url:"{{ url('/serieNumero/listaSerieNumeroGrid') }}",
        datatype: "json",
        height: 300,
        width: 800,
   	colNames:['Acción','Acción','Acción','Código','Serie','Folio/Número'],
   	colModel:[
                {name:'bAccion',index:'bAccion', width:70,sortable: false,hidden:selecc },
                {name:'bAccion2',index:'bAccion2', width:70,sortable: false,hidden:edit },
                {name:'bAccion3',index:'bAccion3', width:70,sortable: false,hidden:eliminar },
                {name:'bCodigo',index:'bCodigo',sorttype:"integer", width:30},
   		{name:'bSerie',index:'bSerie', width:30},
   		{name:'bNumero',index:'bNumero',sorttype:"integer", width:50}       
   	],
        rowNum:10,
   	rowList:[10,20,30],
        viewrecords: true,
        sortname: 'bCodigo',
        sortorder: "desc",
   	pager: '#grid-page-b-serie-numero',
});
jQuery("#grid-b-serie-numero").jqGrid('navGrid',"#grid-page-b-serie-numero",{edit:false,add:false,del:false,search:false});
//jQuery("#grid-b-serie-numero").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

</script>