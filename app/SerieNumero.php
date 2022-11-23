<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SerieNumero extends Model
{
    //c_serie_numero
    protected $table='c_serie_numero';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getTotallistaGrid($idContri){
        $retVal = DB::table($this->table)
                    ->where('id_contribuyente_padre', $idContri)
                    ->count();
        return $retVal;
    } 
    
    public function getListaGrid($idContri,$sidx,$sord,$start,$limit){
        $retVal = DB::table($this->table);
        $retVal->where('id_contribuyente_padre', $idContri);
        $retVal->orderBy($sidx,$sord);
        $retVal->skip($start);
        $retVal->take($limit);
        $resultado=$retVal->get();
        return $resultado;
    }     
    
    public function getListByIdContribuyente($id){
        $retVal = DB::table($this->table)
                ->where('id_contribuyente_padre',$id)
                ->get();
        return $retVal;
    }
    
    public function removeById($id){
        DB::table($this->table)->where('id',$id)->delete();
    }
    
    public function updateById($id,$serie,$numero){
        DB::table($this->table)->where('id',$id)
                ->update(['serie' => $serie,'numero'=>$numero]);
    }

    public function updateNumeroByIdContribuyentePadreAndSerie($idContrPadre,$serie,$numero){
        DB::table($this->table)
                ->where('id_contribuyente_padre',$idContrPadre)
                ->where('serie',$serie)
                ->update(['numero'=>$numero]);
    }
    
    public function existeSerie($idContribuyentePadre,$serie){
        $retVal = DB::table($this->table)
                ->where('id_contribuyente_padre',$idContribuyentePadre)
                ->where('serie',$serie)
                ->get();
        return $retVal;
    }
    
    public function agregar($idContribuyentePadre,$serie,$numero){
        $serieNum=new SerieNumero();
        $serieNum->id_contribuyente_padre=$idContribuyentePadre;
        $serieNum->serie=$serie;
        $serieNum->numero=$numero;
        $serieNum->save();
    }
    
    public function comboSerieByIdContribuyentePadre($idCont){
        return DB::table($this->table)
                ->where('id_contribuyente_padre',$idCont)
                ->pluck('serie', 'serie');
    }
    
}
