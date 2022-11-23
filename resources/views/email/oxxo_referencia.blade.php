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
        <h1>FactuNX Referencia OXXO</h1>
        <br/>
        <div id="contenido">
            @if ($isComprado)
                <h2><strong>Gracias por comprar nuestro producto</strong></h2>
            @else
                <p><strong>Producto por comprar</strong></p>
            @endif
            <table border="1" bgcolor="#FFFFFF" style="width: 100%;" >
                <tr>
                    <td>Nombre</td>
                    <td>Descripci&oacute;n</td>
                    <td>Cantidad</td>
                    <td>Precio</td>
                </tr>
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->timbre }}</td>
                    <td>{{ $producto->precio }}</td>
                </tr>
            </table>
            @if ($isComprado)
                <p><strong>Puede empezar a timbrar los documentos que necesite.</strong></p>
            @else
                <p><strong>Se adjunta referencia el cual tiene vigencia 48 Hrs. Agradecemos su pago en la brevedad</strong></p>
            @endif
            
        </div>
    </body>
</html> 