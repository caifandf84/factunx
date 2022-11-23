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
            <h1><strong>{{$name }}</strong> Quiere emitir a su nombre </h1>
            Correo : {{$email }}<br/><br/>
            Sí autoiza acceso al sistema dar clic en el siguiente boton:<br/><br/>
            <center><a href="{{$url }}" class="w3-button w3-blue">Autorizó</a></center><br/>
            En caso de no autorizar eliminar este correo
        </div>
    </body>
</html> 
