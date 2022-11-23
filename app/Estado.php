<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Estado extends Model
{
    //
    
    protected $table='cat_estado';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getComboByPais($idPais){
        return DB::table($this->table)
                ->where('id_pais', '=', $idPais)
                ->orderBy('nombre', 'asc')
                ->pluck('nombre', 'id');
    }
    public function getNombreById($id){
        $retVal = DB::table($this->table)
                    ->where('id', $id)
                    ->pluck('nombre')
                    ->first();
        return $retVal;
    }
}
