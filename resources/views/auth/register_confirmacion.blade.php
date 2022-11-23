@extends('layouts.app')

@section('content')
<div class="container">
     <div class="panel panel-primary">
         <div class="panel-heading">Confirmaci&oacute;n</div>
                <div class="panel-body">
                    <h3>Esta a un paso de Facturar,emitir Recibos,ETC a nombre de <strong>{{$contribuyente->rfc}}</strong> , se requiere</h3>
                    <h3>confirmaci&oacute;n de <strong>{{$contribuyente->razon_social}}</strong> por correo electronico </h3>
                    <h3><strong>Una vez confirmado, le llegara un correo electronico a {{$correoReceptor}} de bienvenida.</strong></h3>
                </div>
     </div>
</div>
@endsection
