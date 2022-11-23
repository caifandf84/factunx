<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Pais extends Model
{
    //
    protected $table='cat_pais';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getComboByPais(){
        return DB::table($this->table)
                ->pluck('nombre', 'id');
    }
    
    public function getNombreById($id){
        $retVal = DB::table($this->table)
                    ->where('id', $id)
                    ->pluck('nombre')
                    ->first();
        return $retVal;
    }
    
    public function getIdByNombre($nombre){
        $retVal = DB::table($this->table)
                    ->where('nombre','like','%'.$nombre.'%')
                    ->pluck('id')
                    ->first();
        return $retVal;
    }
    
}
