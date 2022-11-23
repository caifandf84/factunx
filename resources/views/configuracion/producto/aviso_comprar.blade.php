@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Comprar Timbres</div>
                <div class="panel-body">
                    <h3>Se requieren timbres para emitir documentos</h3>
                    <div class="form-group-sm">
                        <label for="Timbres Disponibles" class="col-md-4 control-label">Timbres Disponibles</label>
                        <div class="col-md-4">
                            <input id="timbres_dispo" type="text" class="form-control" name="timbres_dispo" value="{{$timbreDosponible}}" disabled="true">
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <a class="btn btn-primary" href="{{ url('/productos') }}">
                                    Comprar
                                </a>
                            </div>
                        </div>
                </div>
            </div>
            @endsection