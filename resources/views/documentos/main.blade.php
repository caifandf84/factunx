@extends('layouts.app')

@section('content')

<div class="container">
 <div class="row">
     <div class="col-sm-6" >
         <form action="{{url('/documentos/tipo').'/1/'}}{{Crypt::encrypt('1')}}"  method="get" id="form_emision" >
         <div class="thumbnail" id="ingresa_emision" style="cursor:pointer; cursor: hand; background-color: #4AB085;"  >
             <img src="https://www.conuxi.com/factunx_frameworks/images/documento_emitir_3.jpg" style="height: 180px;width: 100%;" alt="Emision" >
            <div class="caption" style="height: 100px;" >
                <h3><strong>Crear Documento.</strong></h3>
                <h4>Factura Electronica, Recibo de Honorarios.</h4>
            </div>
         </div>
         </form>
     </div>
     <div class="col-sm-6" >
         <form action="{{url('/documentos/ver/lista/emitidos')}}"  method="get" id="form_ver_emision" >
         <div class="thumbnail" id="ver_emision" style="cursor:pointer; cursor: hand; background-color: #4AB085;" >
            <img src="https://www.conuxi.com/factunx_frameworks/images/lista_documentos.jpg" style="height: 180px;width: 100%;"  alt="Lista de Documentos" >
            <div class="caption" style="height: 100px;" >
                <h3><strong>Descargar</strong> PDF,XML de Documentos Generados</h3>
            </div>
         </div>
         </form>
     </div>
     <div class="col-sm-6" >
         <form action="{{url('/documentos/ver/cancelar')}}"  method="get" id="form_ver_cancelar" >
         <div class="thumbnail" id="ver_cancelar" style="cursor:pointer; cursor: hand; background-color: #4AB085;" >
            <img src="https://www.conuxi.com/factunx_frameworks/images/cancela_cfdi.jpg" style="height: 180px;width: 100%;"  alt="Cancelación de Documentos" >
            <div class="caption" style="height: 100px;" >
                <h3><strong>Cancelar Documentos ante el SAT</strong></h3>
            </div>
         </div>
         </form>
     </div>     
     <div class="col-sm-6" >
         <form action="{{url('/documentos/ver/tmpEmision')}}"  method="get" id="form_ver_doc_pendientes" >
         <div class="thumbnail" id="ver_doc_pendientes" style="cursor:pointer; cursor: hand; background-color: #4AB085;" >
            <img src="https://www.conuxi.com/factunx_frameworks/images/pendiente_documentos.png" style="height: 180px;width: 100%;"  alt="Cancelación de Documentos" >
                <div class="caption" style="height: 100px;" >
                    <h3><strong>Documentos Pendietes por Emitir</strong></h3>
                </div>
         </div>
         </form>
     </div> 
     <!--div class="col-sm-6">
         <div class="thumbnail" id="ingresa_recepcion" style="cursor:grab" >
            <form action="{{url('/documentos/tipo').'/'}}{{Crypt::encrypt('1')}}"  method="get" id="form_recepcion" > 
            <img src="https://www.conuxi.com/factunx_frameworks/images/rectangulo_gris.jpg" alt="Recepcion" style="width:100%">
            <div class="caption">
              <h3>Quiere comprobar Documentos que sean timbrados a tu nombre correctamente</h3>
            </div>
            </form>
         </div>
     </div-->
</div>
 <!--div class="row">
     <div class="col-sm-12">
         <form action="{{url('/documentos/ver/lista/emitidos')}}"  method="get" id="form_ver_emision" >
         <div class="thumbnail" id="ver_emision" style="cursor:pointer; cursor: hand;" >
            <img src="https://www.conuxi.com/factunx_frameworks/images/lista_documentos.jpg" style="height: 180px;width: 100%;"  alt="Lista de Documentos" >
            <div class="caption">
                <h3><strong>Encontrar</strong> PDF,XML de Documentos Generados</h3>
            </div>
         </div>
         </form>
     </div>
</div-->    
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#ingresa_emision').click(function(){
      $('#form_emision').submit();
    });
    $('#ver_emision').click(function(){
      $('#form_ver_emision').submit();
    });
    $('#ver_cancelar').click(function(){
      $('#form_ver_cancelar').submit();
    });
    $('#ver_doc_pendientes').click(function(){
      $('#form_ver_doc_pendientes').submit();
    });
});
</script>

@endsection