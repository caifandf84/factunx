<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contribuyente extends Model
{
    //
    protected $table='c_contribuyente';
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getContribuyenteByRFC($rfc){
        $valCont = DB::table($this->table)
                    ->where('rfc', '=', $rfc)
                    ->first();
        return $valCont;
    }
    
    public function getContribuyenteById($id){
        $valCont = DB::table($this->table)
                    ->where('id', '=', $id)
                    ->first();
        return $valCont;
    }
    
    public function guardaOActualizar(Pojo\DocumentoEmision $emision){
        $rfc=$emision->rfc;
        $retVal = null;
        $pais = null;
        $valCont = DB::table($this->table)
                ->where('rfc', '=', $rfc)
                ->first();
        if($emision->pais!=null){
        $pais = DB::table('cat_pais')
                ->where('id', '=', $emision->pais)
                ->first();
        }
        if($emision->colonia==null && $emision->cmbColonia!=null ){
            $col = DB::table('cat_colonia')
                ->where('id', '=', $emision->cmbColonia)
                ->first();
            $emision->colonia= ($col!=null?$col->nombre:null);
        }
        if($emision->estado==null && $emision->cmbEstado!=null){
            $edo = DB::table('cat_estado')
                ->where('id', '=', $emision->cmbEstado)
                ->first();
            $emision->estado= ($edo!=null?$edo->nombre:null);
        }
        if($emision->municipio==null && $emision->cmbMunicipio !=null){
            $mpo = DB::table('cat_municipio_edo')
                ->where('id', '=', $emision->cmbMunicipio)
                ->first();
            $emision->municipio= ($mpo!=null?$mpo->nombre:null);
        }
        if($valCont!=null){
            DB::table($this->table)->where('id', $valCont->id)
            ->update(['razon_social' => $emision->razonSocial,
                      'calle' => $emision->calle,'localidad' => $emision->localidad,
                      'codigo_postal' => $emision->codigoPostal,
                      'num_ext' => $emision->numExt,'id_codigo_postal' => $emision->codigoPostal,
                      'colonia' => $emision->colonia,
                      'num_int' => $emision->numInt,'id_colonia' =>$emision->cmbColonia,
                      'municipio' => $emision->municipio,
                      'id_municipio' => $emision->cmbMunicipio,'estado' => $emision->estado,
                      'id_estado' => $emision->cmbEstado,
                      'pais' => ($pais!=null?$pais->nombre:null),'id_pais' => ($pais!=null?$pais->id:null),
                      'correo_electronico' => $emision->email,'celular'=> $emision->cell]);
            $retVal=DB::table($this->table)->where('id', '=', $valCont->id)->first();
        }else{
                    $contribuyente=new Contribuyente();
                    $contribuyente->razon_social=$emision->razonSocial;
                    $contribuyente->rfc=$emision->rfc;
                    $contribuyente->calle=$emision->calle;        
                    $contribuyente->localidad=$emision->localidad;
                    $contribuyente->codigo_postal=$emision->codigoPostal;
                    $contribuyente->num_ext=$emision->numExt;
                    $contribuyente->id_codigo_postal=$emision->codigoPostal;
                    $contribuyente->colonia=$emision->colonia;
                    $contribuyente->num_int=$emision->numInt; 
                    $contribuyente->id_colonia=$emision->cmbColonia;  
                    $contribuyente->municipio=$emision->municipio;  
                    $contribuyente->id_municipio=$emision->cmbMunicipio;  
                    $contribuyente->estado=$emision->estado;  
                    $contribuyente->id_estado=$emision->cmbEstado;  
                    if($pais!=null){
                        $contribuyente->pais=$pais->nombre;  
                        $contribuyente->id_pais=$pais->id;
                    }
                    $contribuyente->tipo_persona=$emision->tipoPersona; 
                    $contribuyente->id_regimen_fiscal=$emision->idRegimenFiscal; 
                    $contribuyente->regimen_fiscal=$emision->regimenFiscalDesc; 
                    $contribuyente->correo_electronico=$emision->email;  
                    $contribuyente->celular=$emision->cell;  
                    $contribuyente->save();
                    $retVal=$contribuyente;
        }
        return $retVal;
    }
    
    public function actualizaByRFC($contribuyente){
        $retVal=null;
        $valCont = DB::table($this->table)
                ->where('rfc', '=', $contribuyente->rfc)
                ->first();
        if($valCont!=null){
            DB::table($this->table)->where('id', $valCont->id)
            ->update(['razon_social' => $contribuyente->razon_social,
                      'calle' => $contribuyente->calle,
                      'localidad' => $contribuyente->localidad,
                      'codigo_postal' => $contribuyente->codigo_postal,
                      'num_ext' => $contribuyente->num_ext,
                      'id_codigo_postal' => $contribuyente->codigo_postal,
                      'colonia' => $contribuyente->colonia,
                      'num_int' => $contribuyente->num_int,
                      'id_colonia' =>$contribuyente->id_colonia,
                      'municipio' => $contribuyente->municipio,
                      'id_municipio' => $contribuyente->id_municipio,
                      'estado' => $contribuyente->estado,
                      'id_estado' => $contribuyente->id_estado,
                      'pais' => $contribuyente->pais,
                      'id_pais' => $contribuyente->id_pais,
                      'tipo_persona' => $contribuyente->tipo_regimen,
                      'id_regimen_fiscal' => $contribuyente->id_regimen_fiscal,
                      'regimen_fiscal' => $contribuyente->regimen_fiscal,
                      'correo_electronico' => $contribuyente->correo_electronico,
                      'celular'=> $contribuyente->celular]);
            $retVal=DB::table($this->table)->where('id', '=', $valCont->id)->first();
        }
        return $retVal;
        
    }
    
    public function getListaGrid($sidx,$sord,$start,$limit,$filterQuery,$idCont){
        $query = DB::table($this->table);
        //$query->join('t_usuario_contribuyente_emisor as cemisor', 'cemisor.id_contribuyente', '!=', $this->table.'.id');
        if($idCont!=null){
            $query->where('id', '!=',$idCont);
        }
        foreach ($filterQuery as $val) {     
                    $query->where($val->field, 'like', '%'.$val->data.'%');
        }  
        $query->orderBy($sidx,$sord)->skip($start)->take($limit);
        return $query->get();
    }
    public function getTotallistaGrid($filterQuery,$idCont){
        $query = DB::table($this->table);
        //$query->join('t_usuario_contribuyente_emisor as cemisor', 'cemisor.id_contribuyente', '!=', $this->table.'.id');
        if($idCont!=null){
            $query->where('id', '!=',$idCont);
        }
        foreach ($filterQuery as $val) {     
                    $query->where($val->field, 'like', '%'.$val->data.'%');
        }         
        return $query->count();
    } 
}
