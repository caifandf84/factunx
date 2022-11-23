@extends('layouts.app')
@section('content')
<!--muestra lista de documentos-->
<link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css">
<link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.theme.css">
<!-- Scripts -->
<script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<link href="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script src="https://www.conuxi.com/factunx_frameworks/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script> 
<div class="container" >
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">Documentos para timbrar sin concluir</div>
            <div class="panel-body">
                <div class="row"> 
                    <hr>
                </div>
                <div class="row"> 
                    @include('documentos.tmpEmision.grid')
                </div>
            </div>
        </div>
    </div>   
</div>
<script type="text/javascript" >
    
</script>
@endsection
