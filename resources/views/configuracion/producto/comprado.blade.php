@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        @isset($mensaje)
        <ul class="list-group" >
            <li class="list-group-item list-group-item-success" >{{ $mensaje }}</li>
        </ul>
        @endisset
    </div>
    <div class="row">
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
            <div class="panel-heading">Lista de Pagos</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-2"><p class="text-success" >Timbres Comprados</p></div>
                    <div class="col-sm-2"><strong>{{$contEmisor->timbres_contradados}}</strong></div>
                    <div class="col-sm-2"><p class="text-success" >Timbres Gastados</p></div>
                    <div class="col-sm-2"><strong>{{$contEmisor->timbres_gastados}}</strong></div>
                    <div class="col-sm-2"><p class="text-success" >Timbres Restantes</p></div>
                    <div class="col-sm-2"><strong>{{$contEmisor->timbres_restantes}}</strong></div>
                </div>
                <div class="table table-responsive">  
                    <table id="grid-pagos"></table>
                    <div id="grid-page-pagos"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12"><a href="{{url('/productos')}}" class="btn btn-primary">Comprar Timbres</a></div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                jQuery("#grid-pagos").jqGrid({
                    url: "{{ url('/producto/comprado/listaProductosGrid') }}",
                    datatype: "json",
                    height: 300,
                    colNames: ['Producto', 'Precio','Referencia','Tipo de Pago','Estatus','Fecha'],
                    colModel: [
                        {name: 'productoNombre', index: 'productoNombre', width: 200, align: "center"},
                        {name: 'precio', index: 'precio', width: 100, align: "center"},
                        {name: 'referencia', index: 'referencia', width: 250, align: "center"},
                        {name: 'tipoPago', index: 'tipoPago', width: 100, align: "center"},
                        {name: 'estatus', index: 'estatus', width: 100, align: "center"},
                        {name: 'created_at', index: 'created_at', width: 150, align: "center",sorttype: "date"}
                    ],
                    rowNum: 10,
                    rowList: [10, 20, 30],
                    sortname: 'created_at',
                    sortorder: "desc",
                    viewrecords: true,
                    pager: '#grid-page-pagos',
                    caption: "Pagos"
                });
                jQuery("#grid-pagos").jqGrid('navGrid', "#grid-page-pagos", {edit: false, add: false, del: false});
            });
        </script>
    </div>
    
</div>
@endsection