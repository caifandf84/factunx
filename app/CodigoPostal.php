<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CodigoPostal extends Model
{
    //
    protected $table='cat_codigo_postal';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getComboByColonia($idcolonia){
        $retVal = DB::table($this->table)
                ->where('id_colonia', '=', $idcolonia)
                ->orderBy('nombre', 'asc')
                ->get();
        return $retVal;
    }  
    
    public function getByColonia($idcolonia){
        $retVal = DB::table($this->table)
                ->where('id_colonia', '=', $idcolonia)
                ->first();
        return $retVal;
    }  
    
}
