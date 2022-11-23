<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Concepto extends Model
{
    //
    protected $table = 'c_concepto';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    
    public function guardar($id, $nombre,$idUnidad,$preUnitario,$identificacion=null,$predial=null,$idCont,$codigoBarra,$idProdServ) {
        $concepto=new Concepto();
        $concepto->nombre=$nombre;
        $concepto->id=$id;
        $concepto->precio_unitario=$preUnitario;
        $concepto->identificacion=$identificacion;
        $concepto->predial=$predial;
        $concepto->id_unidad=$idUnidad;
        $concepto->id_contribuyente=$idCont;
        $concepto->codigo_barra=$codigoBarra;
        $concepto->id_producto_servicio=$idProdServ;
        $concepto->save();
        return $concepto;
    }
    public function eliminarTodo($idCont){
        DB::table($this->table)
                ->where($this->table.'.id_contribuyente', '=', $idCont)
                ->delete();
    }
    
    public function getListaByCodigo($codigo,$idCont){
        $query = DB::table($this->table)
                ->join('c_unidad_prod', 'c_unidad_prod.id', '=', $this->table.'.id_unidad')
                ->where($this->table.'.id_contribuyente', '=', $idCont)
                ->where($this->table.'.id', 'like', '%'.$codigo.'%')
                ->select($this->table.'.id',$this->table.'.nombre',$this->table.'.precio_unitario'
                        ,$this->table.'.predial',$this->table.'.identificacion',$this->table.'.id_contribuyente'
                        ,'c_unidad_prod.nombre as unidad_nom',$this->table.'.codigo_barra',$this->table.'.id_unidad');
        return $query->get();
    }
    
    public function getTotallistaGrid($filterQuery,$idCont){
        $query = DB::table($this->table);
        $query->join('c_unidad_prod', 'c_unidad_prod.id', '=', $this->table.'.id_unidad');
        $query->where($this->table.'.id_contribuyente', '=', $idCont);
        foreach ($filterQuery as $val) {     
                    $query->where($val->field, 'like', '%'.$val->data.'%');
        }
        $query->select($this->table.'.id',$this->table.'.nombre',$this->table.'.precio_unitario'
                        ,$this->table.'.predial',$this->table.'.identificacion',$this->table.'.id_contribuyente'
                        ,'c_unidad_prod.nombre as unidad_nom',$this->table.'.codigo_barra',$this->table.'.id_unidad');
        return $query->count();
    }
    
    public function getListaGrid($sidx,$sord,$start,$limit,$filterQuery,$idCont){
        $query = DB::table($this->table);
        $query->join('c_unidad_prod', 'c_unidad_prod.id', '=', $this->table.'.id_unidad');
        $query->where($this->table.'.id_contribuyente', '=', $idCont);
        foreach ($filterQuery as $val) {     
                    $query->where($val->field, 'like', '%'.$val->data.'%');
        } 
        $query->select($this->table.'.id',$this->table.'.nombre',$this->table.'.precio_unitario'
                        ,$this->table.'.predial',$this->table.'.identificacion',$this->table.'.id_contribuyente'
                        ,'c_unidad_prod.nombre as unidad_nom',$this->table.'.codigo_barra',$this->table.'.id_unidad'
                        ,$this->table.'.id_producto_servicio');
        $query->orderBy($sidx,$sord)->skip($start)->take($limit);
        return $query->get();
    }
    
    public function getByIdyContribuyente($id,$idCont){
        $query = DB::table($this->table)
                ->join('c_unidad_prod', 'c_unidad_prod.id', '=', $this->table.'.id_unidad')
                ->where($this->table.'.id_contribuyente', '=', $idCont)
                ->where($this->table.'.id', '=', $id)
                ->select($this->table.'.id',$this->table.'.nombre',$this->table.'.precio_unitario'
                        ,$this->table.'.predial',$this->table.'.identificacion',$this->table.'.id_contribuyente'
                        ,'c_unidad_prod.nombre as unidad_nom',$this->table.'.codigo_barra',$this->table.'.id_unidad');
        return $query->first();
    }

    public function getByCodigoBarrasyContribuyente($codigoBarras,$idCont){
        $query = DB::table($this->table)
                ->join('c_unidad_prod', 'c_unidad_prod.id', '=', $this->table.'.id_unidad')
                ->where($this->table.'.id_contribuyente', '=', $idCont)
                ->where($this->table.'.codigo_barra', '=', $codigoBarras)
                ->select($this->table.'.id',$this->table.'.nombre',$this->table.'.precio_unitario'
                        ,$this->table.'.predial',$this->table.'.identificacion',$this->table.'.id_contribuyente'
                        ,'c_unidad_prod.nombre as unidad_nom',$this->table.'.codigo_barra',$this->table.'.id_unidad');
        return $query->first();
    }
    
    public function getListaExport($idCont){
        DB::enableQueryLog();
        $query = DB::table($this->table)
                ->join('c_unidad_prod', 'c_unidad_prod.id', '=', $this->table.'.id_unidad')
                ->where($this->table.'.id_contribuyente', '=', $idCont)
                ->select($this->table.'.id',$this->table.'.nombre',$this->table.'.precio_unitario'
                        ,$this->table.'.predial',$this->table.'.identificacion',$this->table.'.id_contribuyente'
                        ,'c_unidad_prod.nombre as unidad_nom',$this->table.'.codigo_barra');
        Log::error(DB::getQueryLog());
        return $query->get();
        
    }
    
}
