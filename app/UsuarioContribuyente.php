<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class UsuarioContribuyente extends Model
{
    //
    protected $table='t_usuario_contribuyente_emisor';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getContribuyenteByUsuario($idUsuario){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->join('c_contribuyente', 'c_contribuyente.id', '=', $this->table.'.id_contribuyente')
                ->where($this->table.'.id_users', '=', $idUsuario)
                ->select('c_contribuyente.*',$this->table.'.*')
                ->first();
        Log::error(DB::getQueryLog());
        return $retVal;
    }
    
    public function getUsuarioByContribuyente($idContribuyente){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->join('users', 'users.id', '=', $this->table.'.id_users')
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.es_padre', '=',true)
                ->select('users.*')
                ->first();
        Log::error(DB::getQueryLog());
        return $retVal;
    }
    
    public function isContribuyenteEmisor($idContribuyente){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->first();
        Log::error(DB::getQueryLog());
        return ($retVal!=null?true:false);
    }
    
    public function isContribuyentePadre($idUser){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_users', '=', $idUser)
                ->first();
        Log::error(DB::getQueryLog());
        return ($retVal!=null && $retVal->es_padre==true?true:false);
    }

    public function actualizarArchivoImgPdf($idUser,$idContribuyente,$idImgPdf){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.id_users', '=', $idUser)      
                ->update(['id_archivo_img_pdf' => $idImgPdf]);
        Log::error(DB::getQueryLog());
        return ($retVal!=null?true:false);
    }     
    
    public function actualizarArchivosPasswd($idUser,$idContribuyente,$idCer,$idKey,$passwd){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.id_users', '=', $idUser)      
                ->update(['contrasenia_sello' => $passwd,'id_archivo_cer' => $idCer,'id_archivo_key' => $idKey]);
        Log::error(DB::getQueryLog());
        return ($retVal!=null?true:false);
    }    
    
    public function agregarTimbres($idContribuyente,$numTimbres){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.es_padre', '=', true)
                ->first();
        $totalTimbres=$retVal->timbres_contradados+$numTimbres;
        $totalRestantes=$retVal->timbres_restantes+$numTimbres;
                DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.es_padre', '=', true)        
                ->update(['timbres_contradados' => $totalTimbres,'timbres_restantes' => $totalRestantes]);
        Log::error(DB::getQueryLog());
        return ($retVal!=null?true:false);
    }    
    
    public function usarTimbre($idContribuyente){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.es_padre', '=', true) 
                ->first();
        $totalGastados=$retVal->timbres_gastados+1;
        $totalRestante=$retVal->timbres_contradados - $totalGastados;
                DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.es_padre', '=', true)         
                ->update(['timbres_gastados' => $totalGastados,
                            'timbres_restantes' => $totalRestante]);
        Log::error(DB::getQueryLog());
        return ($retVal!=null?true:false);
    } 

    public function getTimbres($idContribuyente){
        DB::enableQueryLog();
        $retVal = DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idContribuyente)
                ->where($this->table.'.es_padre', '=', true) 
                ->first();
        Log::error(DB::getQueryLog());
        return $retVal;
    } 
    
    public function guardar($idUsuario, $idContribuyente) {
        $usuContri=new UsuarioContribuyente();
        $usuContri->id_users=$idUsuario;
        $usuContri->id_contribuyente=$idContribuyente;
        $usuContri->save();
        return $usuContri;
    }    
    /****lista de ususarios****/
    public function getTotalUsuarioslistaGrid($idCont){
        $retVal = DB::table($this->table)
                ->join('users', 'users.id', '=', $this->table.'.id_users')
                ->where($this->table.'.id_contribuyente', '=', $idCont);
                //->select('users.*');      
        return $retVal->count();
    } 
    
    public function getUsuariosListaGrid($sidx,$sord,$start,$limit,$idCont){
        $retVal = DB::table($this->table)
                ->join('users', 'users.id', '=', $this->table.'.id_users')
                ->where($this->table.'.id_contribuyente', '=', $idCont); 
        $retVal->select('users.*');
        $retVal->orderBy("users.".$sidx,$sord)->skip($start)->take($limit);
        return $retVal->get();
    }
}
