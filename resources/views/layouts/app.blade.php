<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FactuNX') }}</title>

    <!-- Styles -->
    
    <!--link href="{{ asset('css/app.css') }}" rel="stylesheet" /-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> 
    
    <!--link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.css">
    <link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.theme.css"-->
    <!-- Scripts -->
    <!--link rel="stylesheet" href="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.theme.css">
    <script src="https://www.conuxi.com/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
    <script src="https://www.conuxi.com/jquery-ui-1.11.4/jquery-ui.js"></script-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script> 
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <!-- Scripts -->
    <script src="https://www.conuxi.com/factunx_frameworks/js/app.js"></script>
    <script type="text/javascript"> 
        /*$(document).ready(function(){
            $(document).ajaxStart(function(){
                $("#loading_app").modal("show");
            });
            $(document).ajaxComplete(function(){
                $("#loading_app").modal("hide");
            }); 
        });*/
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
    <style>
        body{ background-color: #B7CBE1;} 
    </style>
</head>
<!--header>
    <div class="masthead">
        <img src="https://www.conuxi.com/mascoota_files/logo_header.png" height="130" >
    </div>  
</header-->
<body>
    <div id="app">
        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <!--a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a-->
                    @if (Auth::guest())
                        <a class="navbar-brand" href="{{url('/')}}">FactuNX</a>
                    @else
                        <a class="navbar-brand" href="{{url('/home')}}">FactuNX</a>
                    @endif
                </div>
                
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                   
                    <ul class="nav navbar-nav">
                        
                        @if (!empty(Session::get('menus')))
                            @foreach (Session::get('menus') as $menu)
                            @if(isset($menu->children))
                            <li class="dropdown">
                                <a href="#" class="trigger right-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{trans($menu->nombre)}}<span class="caret"></span></a>
                                    <ul class="dropdown-menu sub-menu">
                                    @foreach($menu->children as $children)
                                     <li><a class="trigger right-caret" href="{{url($children->url)}}">{{trans($children->nombre)}}</a>
                                         @if (isset($children->children))
                                           <ul class="dropdown-menu sub-menu">
                                           @foreach($children->children as $children2)
                                             <li><a href="{{url($children2->url)}}">{{trans($children2->nombre)}}</a></li>
                                           @endforeach
                                           </ul>        
                                         @endif
                                         
                                     </li> 
                                    @endforeach 
                                    </ul>
                            </li>
                            @else
                            <li><a href="{{url($menu->url)}}">{{trans($menu->nombre)}}</a></li>
                            @endif
                            
                            @endforeach
                        @endif    
                            
                    </ul>
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        
                    </ul>
                    @if (env('APP_ENV')!='Production')
                        <button type="submit" class="btn btn-default">Ambiente de Pruebas</button>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">@lang('app.menu_login')</a></li>
                            <li><a href="{{ route('register') }}">@lang('app.menu_registrer')</a></li>
                        @else
                            @if (Session::exists('timbres'))
                            <li><a href="{{ url('/producto/comprado/listaProductos') }}">Timbres disponibles: {{Session::get('timbres')}}</a></li>
                            @endif 
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>       
        @yield('content')
    </div>
<div class="modal fade" id="loading_app" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h1>Cargando...</h1>
        </div>
        <div class="modal-body">
          <div class="progress progress-striped active">
                <div class="bar" style="width: 100%;"></div>
          </div>
        </div>
      </div>
      
    </div>
  </div>  
    
        <footer id="footer" class="footer">
            <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="container text-center">   
                        <h3>Software creado para trabajar bajo estandares de SAT</h3>
                        ©2017 CONUXI. All rights reserved <a href="{{url('/terminos_condiciones')}}">T&eacute;rminos y Condiciones</a>
                        <div class="credits">
                            CONUXI <a href="http://www.conuxi.com/">CONUXI.COM</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </footer>    
    
</body>
</html>
