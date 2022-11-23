<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            #contenido
            {
                text-align: center;
                font-family: "Times New Roman", Georgia, Serif;
                font-stretch: expanded;
                font-size: 150%;
                height:300px;
                width: 700px;
                border: solid 1px black;
                background-color: #5FCF80;
                padding: 10px;
                margin: 20px;
            }
        </style>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <title></title>
    </head>
    <body>
        <div id="contenido">
            <p class="bg-info">
                Bienvenido,<br/>
                Ya puede ingresar al sistema FACTUNX, este sistema te proporciona las siguientes caracteristicas:<br/>
                Facturar ante el SAT de manera Facil.<br/>
                Cancelar Facturas.<br/>
                Ver datos estadisticos de tus facturaciones.<br/>
                Agregar tus productos de manera sencilla.<br/>
                Autorizar todos los usuarios que requiera ingresar al sistema<br/><br/>
                <strong>A disfrutar:</strong>
            </p>

            <a class="btn btn-primary" href="{{url('/home')}}">Ingresar</a>
        </div>
    </body>
</html> 
