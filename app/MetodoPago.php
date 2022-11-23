<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class MetodoPago extends Model
{
    //
    protected $table='c_metodo_de_pago';
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
    
    public function getNombreById($id){
        $retVal = DB::table($this->table)
                    ->where('id', $id)
                    ->pluck('nombre')
                    ->first();
        return $retVal;
    } 
    
    public function combo(){
        return DB::table($this->table)->pluck('nombre', 'id');
    }
}
