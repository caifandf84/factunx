@extends('layouts.app')
@section('content')
<div class="container" >
    <div class="row">
      @include('administracion.usuarios.listado')  
    </div>
</div>
@endsection
