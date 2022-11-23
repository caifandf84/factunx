<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class Unidad extends Model
{
    //
    protected $table='c_unidad_prod';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getById($id){
        return DB::table($this->table)->where('id',$id)->first();
    }
    
    public function get(){
        $retVal = DB::table($this->table)->get();
        return $retVal;
    } 
    
    public function combo(){
        return DB::table($this->table)->pluck('nombre', 'id');
    }
    
    public function getNombreById($id){
        $retVal = DB::table($this->table)
                    ->where('id', $id)
                    ->pluck('nombre')
                    ->first();
        return $retVal;
    } 
    
    public function getIdByNombre($nombre){
        $retVal = DB::table($this->table)
                    ->where('nombre', $nombre)
                    ->pluck('id')
                    ->first();
        return $retVal;
    }     
    
}
