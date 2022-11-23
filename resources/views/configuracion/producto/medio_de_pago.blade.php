@extends('layouts.app')
@section('content')
<div class="container">
    <div class="panel panel-success">
        <div class="panel-heading">Forma de Pago</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th class="table-image"></th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th class="column-spacer"></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="table-image"><img src="{{ $item->imagen }}" alt="Producto" class="img-responsive cart-image"></a></td>
                        <td><a>{{ $item->nombre }}</a><br/><a>{{ $item->descripcion }}</a></td>
                        <td>1</td>
                        <td>${{ $subtotal }}</td>
                        <td class=""></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td class="table-image"></td>
                        <td></td>
                        <td class="small-caps table-bg" style="text-align: right">Subtotal</td>
                        <td>${{ $subtotal }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="table-image"></td>
                        <td></td>
                        <td class="small-caps table-bg" style="text-align: right">IVA</td>
                        <td>${{ $impuesto }}</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr class="border-bottom">
                        <td class="table-image"></td>
                        <td style="padding: 40px;"></td>
                        <td class="small-caps table-bg" style="text-align: right">Total</td>
                        <td class="table-bg">${{ $item->precio }}</td>
                        <td class="column-spacer"></td>
                        <td></td>
                    </tr>

                </tbody>
            </table>
            <div class="row">
                <hr>
            </div>
            <div class="row">
                <div class="panel-body">
                    <div class="row">
                        <h3><strong>Como desea pagar el producto</strong></h3>
                        <h5><strong>Seleccione el medio de pago</strong></h5>
                    </div>
                    <div class="row">
                        <div class="btn-group btn-group-justified">
                            <a href="{{url('/producto/comprar/form/conTarjeta')}}?producto={{$item->id}}" class="btn btn-primary">Tarjeta de Cr&eacute;dito</a>
                            <a href="{{url('/producto/comprar/form/conTarjeta')}}?producto={{$item->id}}" class="btn btn-primary">Tarjeta de Debito</a>
                            <a href="{{url('/producto/comprar/form/efectivo')}}?producto={{$item->id}}" class="btn btn-primary">Pago en Efectivo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection