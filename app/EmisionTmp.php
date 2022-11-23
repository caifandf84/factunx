<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmisionTmp extends Model {

    //
    protected $table = 't_emision_tmp';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function getListByIdContribuyenteEmisorOrderAsc($id, $campo) {
        $retVal = DB::table($this->table)
                ->where('id_contribuyente_emisor', $id)
                ->orderBy($campo, 'asc')
                ->get();
        return $retVal;
    }
    public function getUltimoByIdContribuyenteEmisorOrderAsc($id) {
        $retVal = DB::table($this->table)
                ->where('id_contribuyente_emisor', $id)
                ->orderBy('created_at', 'asc')
                ->first();
        return $retVal;
    }

    public function eliminar($id) {
        DB::table($this->table)
                ->where('id', $id)
                ->delete();
    }

    public function getById($id){
        return $retVal = DB::table($this->table)
                        ->where('id',$id)
                        ->first();
    }
    
    public function agregar($idContribuyenteEmisor, $idTipoComprobante, $serie, $numero, $total, $paso, $idContribuyenteReceptor, $procesoEmision) {
        $emisionTmp = new EmisionTmp();
        $emisionTmp->id_contribuyente_emisor = $idContribuyenteEmisor;
        $emisionTmp->proceso_emision = $procesoEmision;
        $emisionTmp->serie = $serie;
        $emisionTmp->numero = $numero;
        $emisionTmp->total = $total;
        $emisionTmp->paso = $paso;
        $emisionTmp->id_tipo_comprobante = $idTipoComprobante;
        $emisionTmp->id_contribuyente_receptor = $idContribuyenteReceptor;
        $emisionTmp->save();
        return $emisionTmp;
    }

    public function actualizar($id,$idContribuyenteEmisor, $idTipoComprobante ,$serie, $numero, $total, $paso, $idContribuyenteReceptor, $procesoEmision) {
        DB::table($this->table)
                ->where('id',$id)
                ->update(['id_contribuyente_emisor'=>$idContribuyenteEmisor,
                            'proceso_emision'=>$procesoEmision,
                            'serie'=>$serie,
                            'numero'=>$numero,
                            'total'=>$total,
                            'paso'=>$paso,
                            'id_tipo_comprobante'=>$idTipoComprobante,
                            'id_contribuyente_receptor'=>$idContribuyenteReceptor]);
        return $id;
    }

    public function getTotalPorEmisor($idContribuyenteEmisor) {
        $query = DB::table($this->table);
        $query->where('id_contribuyente_emisor', '=', $idContribuyenteEmisor);
        return $query->count();
    }
    
    public function getListaGrid($idEmisor,$sidx,$sord,$start,$limit){
        $retVal = DB::table($this->table);
        $retVal->select($this->table.'.*');
        $retVal->where('id_contribuyente_emisor', $idEmisor);
        $retVal->orderBy($sidx,$sord);
        $retVal->skip($start);
        $retVal->take($limit);
        $resultado=$retVal->get();
        return $resultado;
    } 

}
