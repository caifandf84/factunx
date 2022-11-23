<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegimenFiscal extends Model
{
    //
    protected $table='c_regimen_fiscal';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * 
     * @param type $tipo - 1.-Fisica,2.-Moral
     * @return type 
     */
    public function combo($tipo){
        if($tipo==1){
            return DB::table($this->table)
                    ->select($this->table.'.*',$this->table.'.descripcion as nombre')
                    ->where('fisica',true)->get();
        }else{
            return DB::table($this->table)
                    ->select($this->table.'.*',$this->table.'.descripcion as nombre')
                    ->where('moral',true)->get();
        }
    }
    
    public function getDescripcionById($id){
        $retVal = DB::table($this->table)
                    ->where('id', $id)
                    ->pluck('descripcion')
                    ->first();
        return $retVal;
    }      
}
