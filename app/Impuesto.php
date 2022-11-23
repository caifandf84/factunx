<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class Impuesto extends Model
{
    //
    protected $table='c_impuesto';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function get(){
        $retVal = DB::table($this->table)->get();
        return $retVal;
    } 
    
    public function combo(){
        return DB::table($this->table)->pluck('nombre', 'id');
    } 
    
    public function getByTipoImpuesto($idTipoImpuesto){
        $retVal = DB::table($this->table)
                ->where('id_tipo_impuesto', '=', $idTipoImpuesto)
                ->orderBy('nombre', 'asc')
                ->get();
        return $retVal;
    } 
}
