<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class ProductoContribuyente extends Model
{
    //
    protected $table='t_contribuyente_producto';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function guardar($idContribuyente,$idProducto,$idOrder,$tipoDePago,$estatus) {
        $prodContribuyente=new ProductoContribuyente();
        $prodContribuyente->id_contribuyente_emisor=$idContribuyente;
        $prodContribuyente->id_producto=$idProducto;
        $prodContribuyente->id_order=$idOrder;
        $prodContribuyente->tipo_de_pago=$tipoDePago;
        $prodContribuyente->estatus=$estatus;
        $prodContribuyente->save();
        return $prodContribuyente;
    } 
    
    public function guardarConReferencia($idContribuyente,$idProducto,
                                    $idOrder,$tipoDePago,$referencia,$estatus) {
        $prodContribuyente=new ProductoContribuyente();
        $prodContribuyente->id_contribuyente_emisor=$idContribuyente;
        $prodContribuyente->id_producto=$idProducto;
        $prodContribuyente->id_order=$idOrder;
        $prodContribuyente->tipo_de_pago=$tipoDePago;
        $prodContribuyente->estatus=$estatus;
        $prodContribuyente->referencia=$referencia;
        $prodContribuyente->save();
        return $prodContribuyente;
    } 
    
    public function getProdContribuyenteByOrder($idOrder){
        $retVal = DB::table($this->table)
                ->where('id_order', '=', $idOrder);    
        return $retVal->first();
    }  
    
    public function getTotalProductoslistaGrid($idCont){
        $retVal = DB::table($this->table)
                ->join('c_producto', 'c_producto.id', '=', $this->table.'.id_producto')
                ->where($this->table.'.id_contribuyente_emisor', '=', $idCont);    
        return $retVal->count();
    }    
    
    public function getProductosListaGrid($sidx,$sord,$start,$limit,$idCont){
        $retVal = DB::table($this->table)
                ->join('c_producto', 'c_producto.id', '=', $this->table.'.id_producto')
                ->where($this->table.'.id_contribuyente_emisor', '=', $idCont); 
        $retVal->select($this->table.'.*','c_producto.id as idProducto',
                'c_producto.nombre as nomProducto','c_producto.precio as precioProducto',
                'c_producto.descripcion as descProducto');
        $retVal->orderBy($this->table.".".$sidx,$sord)->skip($start)->take($limit);
        return $retVal->get();
    }    
    
}
