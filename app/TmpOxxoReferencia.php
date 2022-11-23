<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class TmpOxxoReferencia extends Model
{
    //
    protected $table = 'tmp_oxxo_referencia';
    
    public function guardar($idContEmisor, $referencia,$idProducto,$idOrder) {
        $tmp=new TmpOxxoReferencia();
        $tmp->id_contribuyente_emisor=$idContEmisor;
        $tmp->referencia=$referencia;
        $tmp->id_producto=$idProducto;
        $tmp->id_order=$idOrder;
        $tmp->save();
        return $tmp;
    }     
    
    public function getByIdOrder($idOrder){
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_order', '=', $idOrder)
                ->first();
        return $retVal;
    } 
    
}
