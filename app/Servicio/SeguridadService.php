<?php

namespace App\Servicio;
/**
 * Description of SeguridadService
 *
 * @author Armando
 */
use Log;
use App\Menu;

class SeguridadService {

    //put your code here
    
    public function obtieneMenuPorIdRol($idRol) {
        //List<RolesMenu> rolesMenu = seguridadDao.encuentraRolConMenu(idRol,0L);
        $rolMenus=(new Menu())->getMenuByRolAndParent($idRol,0);
        Log::info("obtieneMenuPorIdRol()");
        foreach ($rolMenus as $rMenu) {
            $this->ingresaMenuHijos($rMenu,$idRol);
        }
        //Log::error("-----------------");
        //Log::error(print_r($rolMenus, true));
        return $rolMenus;
    }
    private function ingresaMenuHijos($menu, $idRol){
        $hijos = array();
        if($menu!=null){
            $hijos =(new Menu())->getMenuByRolAndParent($idRol,$menu->id); 
        }
        foreach ($hijos as $hijo) {
            $id='children';
            if(property_exists($menu,$id)){
               $menu->$id[]=$hijo;
            }else{
               $hijos = array($hijo); 
               $menu->$id=$hijos;
            }
            $this->ingresaMenuHijos($hijo,$idRol);
        }
    }
    /*
    private function ingresaMenuHijos(RolesMenu rolesMenu, long idRol){
            List<RolesMenu> hijos = new ArrayList<RolesMenu>();
            if(rolesMenu!=null){
                hijos = seguridadDao.encuentraRolConMenu(idRol,rolesMenu.getMenu().getId());
            }
                for(RolesMenu rH: hijos){
                        rolesMenu.getRolesMenu().add(rH);
                    ingresaMenuHijos(rH,idRol);
            }
            return null;
    }*/

}
