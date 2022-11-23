<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Menu extends Model
{
    
    protected $table='s_menu';
    
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function getMenuByRol($idRol){
        $retVal = DB::table($this->table)
                ->join('s_menu_roles', 's_menu_roles.id_menu', '=', $this->table.'.id')
                ->where('s_menu_roles.id_roles', '=', $idRol)
                ->select($this->table.'.*')
                ->get();
        return $retVal;
    }
    public function getMenuByRolAndParent($idRol,$parent){
        $retVal = DB::table($this->table)
                ->join('s_menu_roles', 's_menu_roles.id_menu', '=', $this->table.'.id')
                ->where('s_menu_roles.id_roles', '=', $idRol)
                ->where($this->table.'.parent', '=', $parent)
                ->select($this->table.'.*')
                ->get();
        Log::error(DB::getQueryLog());
        return $retVal;
    }    
}
