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
            background-color: #1179E8;
            padding: 10px;
            margin: 20px;
        }
        </style>
        <title></title>
    </head>
    <body>
        <h1>Documento Emitido {{ $doc->tipoDoc }}</h1>
        <br/>
        <div id="contenido">
        <p><strong>Agradecemos tu preferencia y te informamos el documento {{ $doc->tipoDoc }}
            Anexo envio comprobante fiscal digital.</strong></p>
        </div>
    </body>
</html> 
