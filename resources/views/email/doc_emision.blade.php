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
        <title></title>
    </head>
    <body>
        <h1>Documento Emitido {{ $doc->tipoDoc }}</h1>
        <br/>
        <p>Se genero correctamente el documento <strong>{{ $doc->tipoDoc }}</strong> ante el SAT.<br/>
           Se adjunta y se almacena en sistema para mayor referenca.</p>
        <div id="contenido">
            Se emite con las siguientes caracteristicas:<br/><br/>
            Nombre: {{ $doc->razonSocial }}<br/><br/>
            R.F.C.: {{ $doc->rfc }}<br/><br/>
            Total: {{ $doc->total }} {{ $doc->moneda }}<br/><br/>
        </div>
    </body>
</html> 
