@extends('layouts.app')

@section('content')

<div class="container">
    <div class="jumbotron text-center clearfix">
            <h2>Productos a seleccionar</h2>
            <p><strong>Todos los productos se pueden pagar con Tarjetas de Cr&eacute;dito, Debito o Tiendas de Servicio</strong></p>   
    </div>
    
    @foreach ($products->chunk(4) as $items)
    <div class="row">
        @foreach ($items as $product)
        <div class="col-md-3">
            <div class="thumbnail">
                <div class="caption text-center">
                    <a href="{{ url($product->slug) }}/{{ $product->id }}"><img src="{{ $product->imagen }}" alt="product" class="img-responsive"></a>
                    <a href="{{ url($product->slug) }}/{{ $product->id }}"><h3>{{ $product->nombre }}</h3>
                        <p>$ {{ $product->precio }} M.N</p>
                    </a>
                </div> <!-- end caption -->
            </div> <!-- end thumbnail -->
        </div> <!-- end col-md-3 -->
        @endforeach
    </div> <!-- end row -->
    @endforeach
    <div class="row">
        <div class="alert alert-info">
            <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <strong>Tarjeta de Cr&eacute;dito</strong>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/visa.png" class="img-responsive" alt="Visa" width="60%" height="50">
                    </div>
                    <div class="col-sm-6">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/master_card.png" class="img-responsive" alt="Master Card" width="60%" height="50">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <strong>Tarjeta de Debito</strong>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/citibanamex.png" class="img-responsive" alt="Banamex" width="100%" height="50">
                    </div>
                    <div class="col-sm-3">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/hsbc.png" class="img-responsive" alt="HSBC" width="100%" height="50">
                    </div>
                    <div class="col-sm-3">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/inbursa.png" class="img-responsive" alt="Inbursa" width="100%" height="50">
                    </div>
                    <div class="col-sm-3">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/santander.jpg" class="img-responsive" alt="Santander" width="100%" height="50">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <strong>Efectivo</strong>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/oxxo.png" class="img-responsive" alt="OXXO" width="60%" height="50">
                    </div>
                    <div class="col-sm-6">
                        <img src="https://www.conuxi.com/factunx_frameworks/images/banorte.png" class="img-responsive" alt="Banorte" width="60%" height="50">
                    </div>
                </div>
            </div>    
            </div>
        </div>
    </div>
</div>
@endsection
