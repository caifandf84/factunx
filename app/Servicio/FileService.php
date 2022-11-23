<?php

namespace App\Servicio;
/**
 * Description of FileService
 *
 * @author Armando
 */
use Image;
use Storage;
use File;

class FileService {
    //put your code here
    
    //dev
    
    private $pathFTPImage = "http://www.conuxi.com/factura_files/credenciales/";
    
    private $hostServer = "conuxi.com";
    
    private $pathImageServer = "/home/conuxico/public_html/factura_files/credenciales/";
    
    //prod
    /*
    private $pathFTPImage = "http://www.mascoota.com/mascoota_files/image/blog/";
    
    private $hostServer = "mascoota.com";
    
    private $pathImageServer = "/home/mascoota/public_html/mascoota_files/image/blog/";
    */
    
    /**
     * Redimensiona imagen en caso de error responde un falso
     * @param type $image
     * @param type $largo
     * @param type $ancho
     * @param type $prefix
     * @return boolean
     */
    public function resize($image, $largo, $ancho,$prefix) {
        try {
            $imageRealPath = $image->getRealPath();
            $thumbName = $image->getClientOriginalName();
            
            $img = Image::make($imageRealPath); // use this if you want facade style code
            $img->resize($largo,$ancho);
            return $img->save(storage_path() . $prefix . $thumbName);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Guarda imagen en servidor, responde ruta de imagen.
     * @param type $folder
     * @param type $image
     */
    public function saveTmp($name){
        try{
            $pos = strpos(url(), $this->hostServer);
            if ($pos > 0) {
                File::makeDirectory($this->pathImageServer, 0775, true, true);
                File::move(storage_path().'/'.$name,$this->pathImageServer);
                File::delete(storage_path().'/'.$name); 
            }else{
                //$file=Storage::disk('ftp')->get($folder.'/'.$name);
                //if($file == ''){
                    Storage::disk('ftp')->put('/', file_get_contents(storage_path().'\\'.$name));
                    File::delete(storage_path().'\\'.$name);
                //}
            }
            return $this->pathFTPImage.$folder.'/'.$nameFileRepository;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function existFile($folder,$name) {
        $pos = strpos(url(), $this->hostServer);
        if ($pos > 0) {
            return File::exists($this->pathImageServer.$folder.'/'.$name);
        }else{
            $file = $folder.'/'.$name;
            return Storage::disk('ftp')->exists($file);
        }
    }

    public function deleteFile($folder,$name) {
        $pos = strpos(url(), $this->hostServer);
        if ($pos > 0) {
            return File::delete($this->pathImageServer.$folder.'/'.$name);
        }else{
            $file = $folder.'/'.$name;
            return Storage::disk('ftp')->delete($file);
        }
    }    
    
}
