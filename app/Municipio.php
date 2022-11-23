<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Municipio extends Model
{
    //
    protected $table='cat_municipio_edo';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getComboByEstado($idEstado){
        $retVal = DB::table($this->table)
                ->where('id_estado', '=', $idEstado)
                ->orderBy('nombre', 'asc')
                ->get();
        return $retVal;
    }
    
    public function getNombreById($id){
        $retVal = DB::table($this->table)
                    ->where('id', $id)
                    ->pluck('nombre')
                    ->first();
        return $retVal;
    }    
    
}
