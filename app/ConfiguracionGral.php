<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ConfiguracionGral extends Model
{
    //
    protected $table='s_configuracion_gral';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    
    public function getByNombre($nombre){
        $retVal = DB::table($this->table)
                ->where('nombre','=', $nombre)
                ->first();
        return $retVal;
    }  
    
}
