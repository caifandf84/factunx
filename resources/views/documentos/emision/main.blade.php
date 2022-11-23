@extends('layouts.app')
@section('content')
<!--selecciona tipo de documentos-->
<div class="row">
                        <div class="container-fluid" >    
                            @include('documentos.emision.datos_generales')
                        </div>
                    </div>
@endsection