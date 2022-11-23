<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductoServicio extends Model
{
    //
    protected $table='c_producto_servicio';
    
    public function combo(){
        return DB::table($this->table)->pluck('nombre', 'id');
    }
    
    public function getListaGrid($sidx,$sord,$start,$limit,$filterQuery){
        $query = DB::table($this->table);
        foreach ($filterQuery as $val) {     
                    $query->where($val->field, 'like', '%'.$val->data.'%');
        }   
        $query->orderBy($sidx,$sord);
        $query->skip($start);
        $query->take($limit);
        $resultado=$query->get();
        return $resultado;
    }

    public function getTotallistaGrid($filterQuery){
        $query = DB::table($this->table);
        foreach ($filterQuery as $val) {     
                    $query->where($val->field, 'like', '%'.$val->data.'%');
        }  
        $resultado=$query->count();          
        return $resultado;
    } 
    
}
