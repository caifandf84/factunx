@extends('layouts.app')

@section('content')
<style type="text/css">
    .spacer5 {
        height: 5px;
    }
    @media screen and (min-width: 768px) {
        .input-group-addon {
            min-width:30%;
            text-align:left;
        }
    }
    @media screen and (min-width: 992px) {
        .input-group-addon {
            min-width:60%;
            text-align:left;
        }
    } 
    
</style>
<div class="container ">
    <div class="row">
        <ul class="list-group" >
            @if(isset($mensaje))
            <li class="list-group-item list-group-item-info" >
                {{ $mensaje }}   
            </li>
            @endif
        </ul>
    </div>
    <div class="row">
        <ul class="list-group" >
            @foreach($errors->all() as $error)
            <li class="list-group-item list-group-item-danger" >{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    {{ Form::open(array('url' => 'administracion/user/password/actualizar','method' => 'post')) }} 
    <div class="container ">
    <div class="panel panel-success ">
        <div class="panel-heading">Cambiar Contraseña</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon alert-info">@lang('auth.reg_name'):</span>
                        {!! Form::text('name',$user->name,array('id'=>'name','class' => 'form-control','disabled' => true,'placeholder'=>'Nombre')) !!}
                    </div>  
                </div>
            </div>
            <div class="spacer5" ></div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon alert-info">@lang('auth.reg_email'):</span>
                        {!! Form::text('email',$user->email,array('id'=>'email','class' => 'form-control','disabled' => true,'placeholder'=>'Email')) !!}
                    </div>  
                </div>
            </div>
            <div class="spacer5" ></div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon alert-info">@lang('auth.reg_passwd'):</span>
                        {!! Form::password('old_password',['class' => 'form-control']) !!}
                    </div>  
                </div>
            </div>
            <div class="spacer5" ></div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon alert-info">@lang('auth.reg_new_passwd'):</span>
                        {!! Form::password('password',['class' => 'form-control']) !!}
                    </div>  
                </div>
            </div>
            <div class="spacer5" ></div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon alert-info">@lang('auth.reg_repasswd'):</span>
                        {!! Form::password('verify_password',['class' => 'form-control']) !!}
                    </div>  
                </div>
            </div>
            <div class="spacer5" ></div>
            <div class="row">
                <div class="col-sm-8">
                    {!! Form::submit('Actualizar',['class' => 'btn btn-info']) !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
