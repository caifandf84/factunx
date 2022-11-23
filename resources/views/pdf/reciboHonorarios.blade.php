<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recibo de Honorarios</title>
        <style>
            .caja {
                font-size: 18px;
                font-weight: 400;
                color: #ffffff;
                background: #361AB1;
                margin: 0 0 0px;
                overflow: hidden;
                padding: 15px;
                text-align: center;
                font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif;
            }
            hr.style2 {
                margin: 0 0 0px;
                border-top: 3px double #8c8b8b;
                background: #361AB1;
            }
            .titulo {
                font-family: verdana;
                font-size: 80%;
                font-weight: bold;
                text-align: left;
            }
            .titulo_der_negro {
                font-family: verdana;
                font-size: 80%;
                font-weight: bold;
                text-align: right;
            }
            .titulo_receptor {
                font-family: verdana;
                font-size: 80%;
                text-align: left;
            }
            .contenido_receptor {
                font-family: verdana;
                font-size: 80%;
                font-weight: bold;
                text-align: left;
            }
            .titulo_izq {
                font-family: verdana;
                font-size: 80%;
                font-weight: bold;
                color: blue;
                text-align: right;
            }
            .contenido_izq {
                font-family: verdana;
                font-size: 80%;
                text-align: left;
            }
            .contenido_izq_ff {
                font-family: verdana;
                font-size: 60%;
                text-align: left;
            }
            .contenido {
                font-family: verdana;
                font-size: 60%;
                text-align: right;
            }
            table.detalles {
                border-collapse: collapse;
                width: 100%;
                font-size: 14px;
            }

            th.detalles, td.detalles {
                text-align: left;
                padding: 4px;
                font-size: 14px;
            }
            td.detalles_ff {
                text-align: left;
                padding: 4px;
                font-size: 14px;
            }

            tr.detalles:nth-child(even){background-color: #f2f2f2}

            th.detalles {
                background-color: #4CAF50;
                color: white;
            }
            .content-box-blue {
                background-color: #d8ecf7;
                border: 1px solid #afcde3;
            }
            
            .content-box-white {
                background-color: #ffffff;
                border: 1px solid #313233;
            }
            
            #conceptos.table{
                border: 2px solid #afcde3;
            }
            .footer {
                clear: both;
                position: relative;
                z-index: 10;
                height: 3em;
                margin-top: -3em;
            }
        </style>    
    </head>
    <body>
        <div class="caja">Recibo de Honorarios Versi&oacute;n 3.3</div>
        <table width="100%" >
            <tr>
                <td width="30%">
                    <img src="{{$data['imgEmpresa']}}" style="height: 3.0cm;width: 3.0cm;" />
                </td>
                <td width="70%" align="center" style="line-height:5px;" >
                    <div class="content-box-blue" >
                        <h3><strong>{{$data['razonSocialEmisor']}}</strong></h3>
                        <h4><strong>{{$data['rfcEmisor']}}</strong></h4>
                        <h4><strong>Tipo de Comprobante</strong></h4>
                        <h4><strong>{{$data['tipoComprobante']}}</strong></h4>
                        <h4><strong>R&eacute;gimen Fiscal</strong></h4>
                        <h4><strong>{{$data['regimenFiscalEmisor']}}</strong></h4>
                    </div>
                </td>
            </tr>
        </table>
        <table class="detalles" >
            <tr>
                <th class="detalles" width="35%" >Folio Fiscal</th>
                <th class="detalles" width="35%" >No de Serie del Certificado CSD</th>
                <th class="detalles" width="30%" >No de Serie del CSD del SAT</th>
            </tr>
            <tr>
                <td class="contenido_izq_ff">{{$data['UUID']}}</td>
                <td class="detalles" >{{$data['noCertificado']}}</td>
                <td class="detalles" >{{$data['noCertificadoSAT']}}</td>
            </tr>
            <tr>
                <td class="contenido_izq" colspan="3" >Fecha y Hora de Emisión: {{$data['fechaTimbrado']}} , LUGAR DE EXPEDICION: {{$data['lugarExpedicion']}}</td>
            </tr>
        </table>   
        <hr class="style2">
        <table width="100%" >
            <tr>
                <td class="titulo_receptor" >Cliente:</td>
                <td class="contenido_receptor" colspan="3" >{{$data['razonSocialReceptor']}}</td>
            </tr>
            <tr>
                <td class="titulo_receptor" >RFC CLIENTE:</td>
                <td class="contenido_receptor" >{{$data['rfcReceptor']}}</td>
                <td class="titulo_receptor" >CORREO:</td>
                <td class="contenido_receptor" >{{$data['correoReceptor']}}</td>
            </tr>
            <tr>
                <td class="titulo_receptor" >Uso del CFDI:</td>
                <td class="contenido_receptor" >{{$data['usoCFDI']}}</td>
                <td class="titulo_receptor" >Forma de Pago:</td>
                <td class="contenido_receptor" >{{$data['formaDePago']}}</td>
            </tr>
            <tr>
                <td class="titulo_receptor" >M&eacute;todo de Pago:</td>
                <td class="contenido_receptor" >{{$data['metodoPago']}}</td>
                <td class="titulo_receptor" >Relaci&oacute;n CFDI:</td>
                <td class="contenido_receptor" >{{$data['tipoRelacion']}}  {{$data['uuidRelacionado']}}</td>                
            </tr>
            <?php if($data['condicionesPago'] != '' || $data['serie']!='' || $data['numero']!=''){?>
            <tr>
                <td class="titulo_receptor" >Condiciones de Pago:</td>
                <td class="contenido_receptor" >{{$data['condicionesPago']}}</td>
                <td class="titulo_receptor" >Referencia Interna :</td>
                <td class="contenido_receptor" >{{$data['serie']}} {{$data['numero']}}</td>
            </tr>
            <?php } ?>
        </table>
        <br/>
        <table width="100%" id="conceptos" >
            <tr>
                <th class="detalles" width="10%" >Prod ó Servicio</th>
                <th class="detalles">Descripci&oacute;n</th>
                <th class="detalles" width="60" >Unidad</th>
                <th class="detalles" width="5%" >Cant.</th>
                <th class="detalles" width="5%" >Precio</th>
                <th class="detalles" width="5%" >Descuento</th>
                <th class="detalles" width="5%" >Importe</th>
            </tr>
            <?php foreach ($data['conceptos'] as $key => $value) { ?>
                <tr>
                    <td class="detalles" ><?php echo $value->idSatProductoServicio; ?></td>
                    <td class="detalles" ><?php echo $value->nombre; ?></td>
                    <td class="detalles" ><?php echo $value->unidadNom; ?></td>
                    <td class="detalles" ><?php echo $value->cantidad; ?></td>
                    <td class="detalles" ><?php echo number_format($value->precioUnitario,2); ?></td>
                    <td class="detalles" ><?php echo number_format($value->descuento,2); ?></td>
                    <td class="detalles" ><?php echo number_format($value->importe,2); ?></td>
                </tr>
            <?php } ?>
        </table>
        <hr class="style2">
        <table width="100%" id="conceptos" >
            <tr>
                <td class="detalles" width="60%" >
                    <img src="{{$data['qr']}}"  style="height: 2.5cm;width: 2.5cm;" />
                </td>
                <td class="detalles" width="40%" >
                    <table width="100%" >
                        <tr>
                            <td class="detalles" >IMPORTE:</td>
                            <td class="contenido_izq" >$ {{number_format($data['subTotal'],2)}}</td>
                        </tr> 
                        <?php foreach ($data['totales'] as $key => $value) { ?>
                                <?php 
                                    if($value->nombre=="TRAS" && $value->tipo=="002"){
                                        echo "<tr>";
                                        echo "<td class='detalles' >IVA:</td><td class='contenido_izq' >";
                                        echo "$ ".number_format($value->valor,2);
                                        echo "</td>";
                                        echo "</tr>";
                                        echo "<tr>";
                                        echo "<td class='detalles' >SUBTOTAL:</td><td class='contenido_izq' >";
                                        echo "$ ".number_format(($value->valor+$data['subTotal']),2);
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                        <?php } ?>
                        <?php foreach ($data['totales'] as $key => $value) { ?>
                                <?php
                                    if($value->nombre=="RET" && $value->tipo=="002"){
                                        echo "<tr>";
                                        echo "<td class='detalles' >IVA RET:</td><td class='contenido_izq' >";
                                        echo "$ ".number_format($value->valor,2);
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                        <?php } ?>
                        <?php foreach ($data['totales'] as $key => $value) { ?>
                                <?php  
                                    if($value->nombre=="RET" && $value->tipo=="001"){
                                        echo "<tr>";
                                        echo "<td class='detalles' >ISR RET:</td><td class='contenido_izq' >";
                                        echo "$ ".number_format($value->valor,2);
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                        <?php } ?>
                        <tr>
                            <td class="detalles" >Total:</td>
                            <td class="contenido_izq" >$ {{number_format($data['total'],2)}}</td>
                        </tr> 
                    </table>
                </td>
            </tr>
        </table>
        
        <footer>
            <div class="titulo content-box-blue" >
                Sello Digital del CFDI
            </div>
            <div class="content-box-white contenido_izq_ff" >
                <?php 
                 $selloCDF=$data['selloCFD']; 
                 $lengSelloCFD=strlen($selloCDF);  
                 if($lengSelloCFD>0){
                    $intevalo=130; 
                    $count = $lengSelloCFD / $intevalo;
                    $incIni=0;
                    for ($i = 1; $i <= $count; $i++) {   
                        $sub=substr($selloCDF,$incIni,$intevalo);
                        echo $sub;echo "<br/>";
                        $incIni=$incIni+$intevalo;
                    }
                    if($incIni<$lengSelloCFD){
                        echo substr($selloCDF,$incIni,($lengSelloCFD-$incIni));echo "<br/>";
                    }
                 }
                ?>
            </div>
            <div class="titulo content-box-blue" >
                Sello del SAT
            </div>
            <div class="content-box-white contenido_izq_ff" >
                <?php 
                 $selloSAT=$data['selloSAT']; 
                 $lengSelloSAT=strlen($selloSAT); 
                 if($lengSelloSAT>0){
                    $intevalo=130; 
                    $count = $lengSelloSAT / $intevalo;
                    $incIni=0;
                    for ($i = 1; $i <= $count; $i++) {   
                        $sub=substr($selloSAT,$incIni,$intevalo);
                        echo $sub;echo "<br/>";
                        $incIni=$incIni+$intevalo;
                    }
                    if($incIni<$lengSelloSAT){
                        echo substr($selloSAT,$incIni,($lengSelloSAT-$incIni));echo "<br/>";
                    }
                 }
                ?>
            </div>
            <div class="titulo content-box-blue" >
                Cadena Original del Complemento de Certificacion Digital del SAT:
            </div>
            <div class="content-box-white contenido_izq_ff" >
                <?php 
                 $cadenaOriginal=$data['cadenaOriginal']; 
                 $lengCadenaOriginal=strlen($cadenaOriginal); 
                 if($lengCadenaOriginal>0){
                    $intevalo=130; 
                    $count = $lengCadenaOriginal / $intevalo;
                    $incIni=0;
                    for ($i = 1; $i <= $count; $i++) {   
                        $sub=substr($cadenaOriginal,$incIni,$intevalo);
                        echo $sub;echo "<br/>";
                        $incIni=$incIni+$intevalo;
                    }
                    if($incIni<$lengCadenaOriginal){
                        echo substr($cadenaOriginal,$incIni,($lengCadenaOriginal-$incIni));echo "<br/>";
                    }
                 }
                ?>
            </div>
            
        </footer>
    </body>

</html>
