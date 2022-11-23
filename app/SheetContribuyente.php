<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SheetContribuyente extends Model {

    //
    protected $table = 's_sheet_contribuyente';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function getSheetConfig($idContribuyentePadre){
        $retVal = DB::table($this->table)
                ->where('id_contribuyente',$idContribuyentePadre)
                ->where('asignado',true)
                ->first();
        return $retVal;
    }
    
    public function asignarSheet($idContribuyentePadre){
        $validacion=$this->getSheetConfig($idContribuyentePadre);
        if($validacion==null){
            $retVal = DB::table($this->table)->where('asignado',false)->first();
            if($retVal!=null){
                DB::table($this->table)->where('id',$retVal->id)
                ->update(['id_contribuyente'=>$idContribuyentePadre,'asignado' => true]);
            }
            return $retVal;
        }
        return $validacion;
    }
    
}
