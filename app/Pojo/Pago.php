<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Pojo;

/**
 * Description of Pago
 *
 * @author Armando
 */
class Pago {
    //put your code here
    
    public $id;
    
    public $idDocumento;
    
    public $monedaDR;
    
    public $metodoDePagoDR;
    
    public $numParcialidad;
    
    public $impSaldoAnt;
    
    public $impPagado;
    
    public $impSaldoInsoluto;
    
    public $fechaPago;
    
    public $formaDePagoP;
    
    public $formaDePagoPDesc;
    
    public $monedaP;
    
    public $folio;
    
    public $serie;
    
    public $monto;
    
    public $rFCEmisorCtaOrigen;
    
    public $nombreBanco;
    
    public $ctaOrigen;
    
    public $rFCEmisorCtaBeneficiaria;
    
    public $ctaBeneficiaria;
    
}
