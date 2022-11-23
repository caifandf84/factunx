<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Colonia extends Model
{
    //
    
    protected $table='cat_colonia';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getComboByMunicipio($idMunicipio){
        $retVal = DB::table($this->table)
                ->where('id_municipio_delg', '=', $idMunicipio)
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
