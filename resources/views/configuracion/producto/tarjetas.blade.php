@extends('layouts.app')
@section('content')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://conektaapi.s3.amazonaws.com/v0.3.2/js/conekta.js"></script>
<script type="text/javascript" >
    @if (env('APP_ENV')!='Production')
        //alert('key_EDuhhuwQkKkyzYHz3H5VDzQ')
        Conekta.setPublishableKey('key_EDuhhuwQkKkyzYHz3H5VDzQ');
    @endif
    @if (env('APP_ENV')=='Production')
        //alert('key_SmmPNxqvhryGdFd3YCeDWxQ')
        Conekta.setPublishableKey('key_SmmPNxqvhryGdFd3YCeDWxQ');
    @endif
    Conekta.setLanguage("es"); 

    var conektaSuccessResponseHandler = function (token) {
        console.log("token.id");
        $("#conektaTokenId").val(token.id);
        $("#formPagoTarjeta").submit();
    };
    var conektaErrorResponseHandler = function (response) {
        console.log("response.message_to_purchaser");
        console.log(response.message_to_purchaser);
        $("#msgError").html("");
        var txt='<ul class="list-group" ><li class="list-group-item list-group-item-danger" >';
        txt+=response.message_to_purchaser;
        txt+='</li></ul>';
        $("#msgError").html(txt);
    };
    
    function crearToken(){
                //privada
		//Conekta.setApiKey("key_xRCdmbt98ZjoZVAmsGxG6Q");
		//publica
		//Conekta.setApiKey("key_EDuhhuwQkKkyzYHz3H5VDzQ");
                var nombre=$("#nombre").val();
                var numeroTarjeta=$("#numero-tarjeta").val();
                var mesExpedicion=$("#mes-expedicion").val();
                var anioExpedicion=$("#anio-expedicion").val();
                var cvv=$("#cvv").val();
                var direccion1=$("#direccion1").val();
                var direccion2=$("#direccion2").val();
                var ciudad=$("#ciudad").val();
                var estado=$("#estado").val();
                var zip=$("#zip").val();
                var pais=$("#pais").val();
        var tokenParams = {
                        "card": {
                          "number": numeroTarjeta,
                          "name": nombre,
                          "exp_year": anioExpedicion,
                          "exp_month": mesExpedicion,
                          "cvc": cvv,
                          "address": {
                              "street1": direccion1,
                              "street2": direccion2,
                              "city": ciudad,
                              "state": estado,
                              "zip": zip,
                              "country": pais
                           }
                        }
                      };
        Conekta.token.create(tokenParams, conektaSuccessResponseHandler, conektaErrorResponseHandler);
        return false;
    }
</script>
<div class="container">
    <div class="row">
        <div id="msgError" >
            
        </div>
        @isset($errorConeckt)
        <ul class="list-group" >
            <li class="list-group-item list-group-item-danger" >{{ $errorConeckt }}</li>
        </ul>
        @endisset
        <ul class="list-group" >
            @foreach($errors->all() as $error)
            <li class="list-group-item list-group-item-danger" >{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    {{ Form::open(array('url' => '/producto/comprar/pagar/medioDePago','method' => 'post','id'=>'formPagoTarjeta')) }} 
    <div class="panel panel-success">
        <div class="panel-heading">Pago</div>
        <div class="panel-body">
           <fieldset>
                <input type="hidden" name="conektaTokenId" id="conektaTokenId" value="-1">
                <input type="hidden" name="idProducto" id="conektaTokenId" value="{{$idProducto}}">
                <input type="hidden" name="tipo_pago" id="tipo_pago" value="1">
                <legend>Datos Bancarios</legend>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="card-holder-name">Nombre </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre en la Tarjeta">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="card-number">*Numero de Tarjeta</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="numero-tarjeta" id="numero-tarjeta" placeholder="Debito/Credito Numero de Tarjeta">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="expiry-month">Fecha de Expedicion</label>
                  <div class="col-sm-9">
                    <div class="row">
                      <div class="col-xs-3">
                        <select class="form-control col-sm-2" name="mes-expedicion" id="mes-expedicion">
                          <option>Mes</option>
                          <option value="01">Enero</option>
                          <option value="02">Febrero</option>
                          <option value="03">Marzo</option>
                          <option value="04">Abril</option>
                          <option value="05">Mayo</option>
                          <option value="06">Junio</option>
                          <option value="07">Julio</option>
                          <option value="08">Agosto</option>
                          <option value="09">Septiembre</option>
                          <option value="10">Octubre</option>
                          <option value="11">Noviembre</option>
                          <option value="12">Diciembre</option>
                        </select>
                      </div>
                      <div class="col-xs-3">
                        <select class="form-control"  name="anio-expedicion" id="anio-expedicion">
                          <option value="17">2017</option>
                          <option value="18">2018</option>
                          <option value="19">2019</option>
                          <option value="20">2020</option>
                          <option value="21">2021</option>
                          <option value="22">2022</option>
                          <option value="23">2023</option>
                          <option value="24">2024</option>
                          <option value="25">2025</option>
                          <option value="26">2026</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="cvv">CVV</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Codigo de Seguridad">
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <br>
                    </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="direccion1-name">Dirección1: </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="direccion1" id="direccion1" placeholder="Direccion 1">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="direccion2-name">Dirección2: </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="direccion2" id="direccion2" placeholder="Direccion 2">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="ciudad-name">Ciudad: </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="ciudad" id="ciudad" placeholder="Ciudad">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="estado-name">Estado: </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="estado" id="estado" placeholder="Estado">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="zip-name">Codigo Postal: </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="zip" id="zip" placeholder="Codigo Postal">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="pais-name">Pais: </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="pais" id="pais" placeholder="Pais">
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <br>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9">
                      <button type="button" onclick="crearToken();" class="btn btn-success">Pagar</button>
                  </div>
                </div>
              </fieldset> 
        </div>
    </div>
    {{ Form::close() }}
</div>
@endsection
