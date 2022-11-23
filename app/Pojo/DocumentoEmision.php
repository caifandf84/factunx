<?php

namespace App\Pojo;
/**
 * Description of DocumentoEmision
 *
 * @author Armando
 */
class DocumentoEmision {
    //put your code here
    
    public $idEmision;
    
    public $tipoDoc;
    
    public $idTipoDoc=33;
    
    public $serie;
    
    public $numero;
    
    public $fechaEmision;
    
    public $idFormaPago="1";
    
    public $tipoCambio=1;
    
    public $totalDescuento=0;
    
    public $moneda="MXN";
    
    public $metodoPago="PUE";
    
    public $tipoComprobante;
    
    public $usoCfdi;
    
    public $tipoRelacion;
    
    public $uuidRelacionado;
    
    public $tipoPersona;
    
    public $idRegimenFiscal;
    
    public $regimenFiscalDesc;
    
    public $condicionesPago;
    
    public $paso=0;
    
    public $rfc;
    
    public $cell;
    
    public $email;
    
    public $razonSocial;
    
    public $pais=1;
    
    public $cmbEstado=0;
    
    public $estado;
    
    public $cmbMunicipio=0;
    
    public $municipio;
    
    public $cmbColonia=0;
    
    public $colonia;
    
    public $codigoPostal;
    
    public $calle;
    
    public $numExt;
    
    public $numInt;
    
    public $localidad;
    
    public $porcTasaImp;        
    
    public $descuento;
    
    public $uuid;
    
    public $estatus;
    
    public $descDescuento;
    
    public $otroImporteRet;
    
    public $importeRet;
    
    public $otroImporteTras;
    
    public $importeTras;
    
    public $totalImporteTras;
    
    public $totalImporteRet;
    
    public $subtotal;
    
    public $total;
    
    public $conceptos;
    
    public $pagos;
    
    public $pagosList;
    
    public $impuestos;
    
    public $pedimentos;
    
    public $cuerpoXml;
    
    public $cuerpoPdf;
    
    public $cuerpoCadenaOriginal;
    
    public $cuerpoQr;
    
    public $idEmisionTmp=0;
    
    public $totales;
    
    public function __construct() {
        // allocate your stuff
    }

    public static function withData(array $data) {
        $instance = new self();
        $instance->loadData($data);
        return $instance;
    }
    
     protected function loadData(array $data) {
        foreach($data as $key => $val) {
            if(property_exists(__CLASS__,$key)) {
                $this->$key = $val;
            }
        }
    }
}
