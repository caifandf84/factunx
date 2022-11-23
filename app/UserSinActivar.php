<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserSinActivar extends Model
{
    //
    protected $table='tmp_user_sin_activar';
    
    public function guardar($name,$email,$password, $idContribuyente) {
        $usuTmp=new UserSinActivar();
        $usuTmp->name=$name;
        $usuTmp->email=$email;
        $usuTmp->password=$password;
        $usuTmp->id_contribuyente=$idContribuyente;
        $usuTmp->save();
        return $usuTmp;
    } 
    
    public function getUserById($id){
        $valCont = DB::table($this->table)
                    ->where('id', '=', $id)
                    ->first();
        return $valCont;
    }
    
    public function deleteUserById($id){
        DB::table($this->table)
                    ->where('id', '=', $id)
                    ->delete();
    }
    
}
