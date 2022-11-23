<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Emision extends Model
{
    protected $table='t_emision';
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getTotallistaGrid($filterQuery=null,$idEmisor){
        $retVal = DB::table($this->table);
        $retVal->join('c_contribuyente as receptor', 'receptor.id', '=', $this->table.'.id_contribuyente_receptor');
        $retVal->join('c_contribuyente as emisor', 'emisor.id', '=', $this->table.'.id_contribuyente_emisor');
        $retVal->join('c_tipo_documento as tipoDocumento', 'tipoDocumento.id', '=', $this->table.'.id_tipo_documento');
        $retVal->where('id_contribuyente_emisor', $idEmisor);
        foreach ($filterQuery as $val) {     
                $retVal->where($val->field, 'like', '%'.$val->data.'%');
        }
        //$retVal->count();
        return $retVal->count();
    } 
    
    public function getTotalPorMesAnio($idEmisor,$mes,$anio){
        $retVal = DB::table($this->table)
                    ->where('id_contribuyente_emisor', $idEmisor)
                    ->whereMonth('created_at', '=', $mes)
                    ->whereYear('created_at', '=', $anio)
                    ->count();
        return $retVal;
    }
    
    public function getTotalTimbradosPorMesAnio($idEmisor,$mes,$anio){
        $retVal = DB::table($this->table)
                    ->where('id_contribuyente_emisor', $idEmisor)
                    ->where('estatus','=','Timbrado')
                    ->whereMonth('created_at', '=', $mes)
                    ->whereYear('created_at', '=', $anio)
                    ->count();
        return $retVal;
    }
    
    public function getTotalCanceladoPorMesAnio($idEmisor,$mes,$anio){
        $retVal = DB::table($this->table)
                    ->where('id_contribuyente_emisor', $idEmisor)
                    ->where('estatus','=','Cancelado')
                    ->whereMonth('created_at', '=', $mes)
                    ->whereYear('created_at', '=', $anio)
                    ->count();
        return $retVal;
    }
    
    public function cancelarEmisorPorId($id){
        DB::table($this->table)
            ->where('id', $id)
            ->update(['estatus' => 'Cancelado']);
    }
    
    public function getById($idEmisor){
        $retVal = DB::table($this->table)
                    ->join('c_contribuyente as receptor', 'receptor.id', '=', $this->table.'.id_contribuyente_receptor')
                    ->join('c_contribuyente as emisor', 'emisor.id', '=', $this->table.'.id_contribuyente_emisor')
                    ->join('c_tipo_documento as tipoDocumento', 'tipoDocumento.id', '=', $this->table.'.id_tipo_documento')
                    ->join('t_archivo as arcPdf', 'arcPdf.id', '=', $this->table.'.id_archivo_pdf')
                    ->join('t_archivo as arcXml', 'arcXml.id', '=', $this->table.'.id_archivo_xml')
                    ->where($this->table.'.id', $idEmisor)
                    ->select($this->table.'.*','receptor.rfc as rfcReceptor','receptor.razon_social as razonSocialReceptor',
                            'receptor.correo_electronico as correoElectronicoReceptor','emisor.correo_electronico as correoElectronicoEmision',
                            'emisor.razon_social as razonSocialEmisor','emisor.rfc as rfcEmisor',
                            'arcPdf.nombre as nombrePdf','arcPdf.extension as extPdf','arcPdf.cuerpo as cuerpoPdf',
                            'arcXml.nombre as nombreXml','arcXml.extension as extXml','arcXml.cuerpo as cuerpoXml',
                            'tipoDocumento.nombre as nomTipoDocumento')
                    ->first();
        return $retVal;
    } 

    public function getListaGrid($idEmisor,$sidx,$sord,$start,$limit,$filtro,$filterQuery=null){
        $retVal = DB::table($this->table);
        $retVal->join('c_contribuyente as receptor', 'receptor.id', '=', $this->table.'.id_contribuyente_receptor');
        $retVal->join('c_contribuyente as emisor', 'emisor.id', '=', $this->table.'.id_contribuyente_emisor');
        $retVal->join('c_tipo_documento as tipoDocumento', 'tipoDocumento.id', '=', $this->table.'.id_tipo_documento');
        $retVal->select($this->table.'.*','receptor.rfc as rfcReceptor','receptor.razon_social as razonSocialReceptor','emisor.rfc as rfcEmisor',
                        'emisor.razon_social as razonSocialEmisor','tipoDocumento.nombre as nomTipoDocumento');
        $retVal->where('id_contribuyente_emisor', $idEmisor);
        if($filtro->nombreCliente!=null && $filtro->nombreCliente!=''){
            $retVal->where('receptor.razon_social','like','%'.$filtro->nombreCliente.'%'); 
        }
        if($filtro->rfcCliente!=null && $filtro->rfcCliente!=''){
            $retVal->where('receptor.rfc','like','%'.$filtro->rfcCliente.'%'); 
        }  
        if($filtro->serie!=null && $filtro->serie!=''){
            $retVal->where($this->table.'.serie', $filtro->serie); 
        } 
        if($filtro->folio!=null && $filtro->folio!=''){
            $retVal->where($this->table.'.numero', $filtro->folio); 
        } 
        if($filtro->fechaDesde!=null && $filtro->fechaDesde!='' && $filtro->fechaHasta!=null && $filtro->fechaHasta!=''){
            $dateTimeIni=\DateTime::createFromFormat("d/m/Y H:i", $filtro->fechaDesde);
            $dateTimeFin=\DateTime::createFromFormat("d/m/Y H:i", $filtro->fechaHasta);
            $carbonIni= \Carbon\Carbon::createFromTimestamp($dateTimeIni->getTimestamp());
            $carbonFin= \Carbon\Carbon::createFromTimestamp($dateTimeFin->getTimestamp());
            $retVal->whereBetween($this->table.'.fecha_emision', array($carbonIni,$carbonFin)); 
        }
        if($filterQuery!=null){
            foreach ($filterQuery as $val) {     
                    $retVal->where($val->field, 'like', '%'.$val->data.'%');
            }
        }
        $retVal->orderBy($sidx,$sord);
        $retVal->skip($start);
        $retVal->take($limit);
        $resultado=$retVal->get();
        return $resultado;
    } 
    
    public function geDocumentoByUuid($uuid){
        $retVal = DB::table($this->table)
                    ->where('uuid', $uuid)
                    ->first();
        return $retVal;
    }
    
    function number_pad($number,$n) {
        return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
    }
    
}
