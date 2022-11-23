<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class TipoComprobante extends Model
{
    //
    protected $table='c_tipo_comprobante';
    
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
   
    public function getById($id){
        $retVal = DB::table($this->table)
                ->where('id',$id)
                ->first();
        return $retVal;
    }      
    
}
