<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class Moneda extends Model
{
    //
    protected $table='c_moneda';
    
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
}
