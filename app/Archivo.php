<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Archivo extends Model {

    //
    protected $table = 't_archivo';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function guardar($cuerpo, $nombre,$extension) {
        $archivo=new Archivo();
        $archivo->nombre=$nombre;
        $archivo->extension=$extension;
        $archivo->cuerpo=$cuerpo;
        $archivo->save();
        return $archivo;
    }
    
    public function actualizarPorId($id,$cuerpo, $nombre,$extension) {
        return DB::table($this->table)->where('id',$id)
                ->update(['nombre' => $nombre,'extension' => $extension,'cuerpo' => $cuerpo]);

    }
    
    public function borrar($id){
        DB::table($this->table)->where('id', '=', $id)->delete();
    }
    public function getById($id){
        return DB::table($this->table)->where('id',$id)->first();
    }

}
