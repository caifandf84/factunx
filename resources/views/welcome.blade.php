<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FactuNX</title>
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Candal|Alegreya+Sans">
        <link rel="stylesheet" type="text/css" href="https://www.conuxi.com/factunx_frameworks/Mentor/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.conuxi.com/factunx_frameworks/Mentor/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.conuxi.com/factunx_frameworks/Mentor/css/imagehover.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.conuxi.com/factunx_frameworks/Mentor/css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> 
    </head>
    <body>
        <!--Navigation bar-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{url('/')}}">FACTU<span>NX</span></a>
                </div>
                
                <div class="collapse navbar-collapse" id="myNavbar">
                    @if (env('APP_ENV')!='Production')
                        <button type="submit" class="btn btn-default">Ambiente de Pruebas</button>
                    @endif
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{url('/productos')}}">Precios</a></li>
                        <li><a href="{{ route('login') }}">@lang('app.menu_login')</a></li>
                        <li><a href="{{ route('register') }}">@lang('app.menu_registrer')</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--/ Navigation bar-->
        <!--Banner-->
        <div class="banner">
            <div class="bg-color">
                <div class="container">
                    <div class="row">
                        <div class="banner-text text-center">
                            <div class="text-border">
                                <h2 class="text-dec">Facturación en Linea</h2>
                            </div>
                            <div class="intro-para text-center quote">
                                <p class="big-text">Necesita generar Facturas Electronica.</p>
                                <p class="small-text">FACTUNX. Genera, Verifica, Administras Facturas y otros documentos</p>
                                <p class="small-text">Necesarios para el SAT</p>
                                <a href="{{ route('login') }}" class="btn">Ingresa al Sistema</a>
                            </div>
                            <a href="#feature" class="mouse-hover"><div class="mouse"></div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Banner-->
        <!--Footer-->
        <footer id="footer" class="footer">
            <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="container text-center">   
                        <h3>Software creado para trabajar bajo estandares de SAT <i class="fa fa-whatsapp" style="color:white">55-3028-4415</i> </h3>
                        ©2017 CONUXI. All rights reserved <a href="{{url('/terminos_condiciones')}}">T&eacute;rminos y Condiciones</a>
                        <div class="credits">
                            CONUXI <a href="http://www.conuxi.com/">CONUXI.COM</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </footer>
        <!--/ Footer-->

        <script src="https://www.conuxi.com/factunx_frameworks/Mentor/js/jquery.min.js"></script>
        <script src="https://www.conuxi.com/factunx_frameworks/Mentor/js/jquery.easing.min.js"></script>
        <script src="https://www.conuxi.com/factunx_frameworks/Mentor/js/bootstrap.min.js"></script>
        <script src="https://www.conuxi.com/factunx_frameworks/Mentor/js/custom.js"></script>
        <script>
        var LHCChatOptions = {};
        LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500,domain:'conuxi.com'};
        (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
        var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
        po.src = '//www.conuxi.com/livehelperchat/index.php/esp/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(check_operator_messages)/true/(top)/350/(units)/pixels/(leaveamessage)/true?r='+referrer+'&l='+location;
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })(); 
        </script>
    </body>
</html>
