<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Log;

class TipoDocumento extends Model
{
    //
    protected $table='c_tipo_documento';
    
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
    
    public function getById($id){
        $retVal = DB::table($this->table)
                ->where('id',$id)
                ->first();
        return $retVal;
    }
    
}
