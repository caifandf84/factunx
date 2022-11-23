@extends('layouts.app')

@section('content')
<style  >
#caja_mediana {
    width: 100%;  
    border: 1px solid #222935;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Principal</div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3">
                                <img src="https://www.conuxi.com/factunx_frameworks/images/logoSAT.png" style="height: 80px;width: 250px;" alt="SAT" >
                            </div>
                            <div class="col-lg-9">
                                <p class="lead">
                                    <strong>Es importante saber que todos documentos (Facturas, Recibos, Etc...) son timbrados ante el SAT
                                            con la vesrion 3.3 que requiere dicha Instituci&oacute;n
                                    </strong>
                                </p>
                            </div>
                        </div>
                         <div class="row">
                             <div class="col-sm-5" id="caja_reporte_timbrado"  >
                                 <form action="{{url('/reporte/timbrado')}}/{{$mes}}/{{$anio}}"  method="get" id="form_reporte_timbrado" >
                                    <div id="caja_mediana" style="cursor:pointer; cursor: hand; background-color: #7A1EA1;" >
                                           <img src="https://www.conuxi.com/factunx_frameworks/images/conteo_main.png" style="height: 180px;width: 100%;" alt="Emision" >
                                           <h3 style="color: white;" ><strong>Se timbr&oacute; {{$totalTimbrado}} en {{$mes}}-{{$anio}} Ver </strong></h3>
                                    </div>
                                 </form>
                                </div>
                                <div class="col-sm-7" id="caja_emitir" >
                                    <form action="{{url('/documentos/tipo').'/1/'}}{{Crypt::encrypt('1')}}"  method="get" id="form_emitir" >
                                        <div id="caja_mediana" style="cursor:pointer; cursor: hand; background-color: #00AA8D;" >
                                               <img src="https://www.conuxi.com/factunx_frameworks/images/documento_emitir_3.jpg" style="height: 180px;width: 100%;" alt="Emision" >
                                               <h3 style="color: white;" ><strong>Requiero timbrar una factura</strong></h3>
                                        </div>
                                    </form>    
                                </div>
                             <!--2do-->
                            <div class="col-sm-6" id="caja_cancelar_timbre" >
                                <form action="{{url('/documentos/ver/cancelar')}}"  method="get" id="form_cancelar_timbre" >
                                    <div id="caja_mediana" style="cursor:pointer; cursor: hand; background-color: #455A64;" >
                                           <img src="https://www.conuxi.com/factunx_frameworks/images/timbrado_main.jpg" style="height: 180px;width: 100%;" alt="Emision" >
                                           <h3 style="color: white;" ><strong>Quiere cancelar Facturas</strong></h3>
                                    </div>
                                </form>
                            </div>
                             
                             <div class="col-sm-6" id="caja_timbres_disponibles" >
                                <form action="{{url('/producto/comprado/listaProductos')}}"  method="get" id="form_timbres_disponibles" >    
                                    <div id="caja_mediana" style="cursor:pointer; cursor: hand; background-color: #FF4081;" >
                                           <img src="https://www.conuxi.com/factunx_frameworks/images/carrito_main.jpg" style="height: 180px;width: 100%;" alt="Emision" >
                                           <h3 style="color: white;" ><strong>Cuantos Timbres tengo disponibles</strong></h3>
                                    </div>
                                </form>    
                            </div>
                            
                            </div>
                           
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 $(document).ready(function() {
    $('#caja_emitir').click(function(){
      $('#form_emitir').submit();
    });
    $('#caja_reporte_timbrado').click(function(){
      $('#form_reporte_timbrado').submit();
    });
    $('#caja_cancelar_timbre').click(function(){
      $('#form_cancelar_timbre').submit();
    });
    $('#caja_timbres_disponibles').click(function(){
      $('#form_timbres_disponibles').submit();
    });    
});
</script>
@endsection
