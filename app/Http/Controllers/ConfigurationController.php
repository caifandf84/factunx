<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Validator;
use Image;

class ConfigurationController extends Controller {

    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexConcepto() {
        $idContEmisor= $this->getIdContEmisor();
        $sheetCont=(new \App\SheetContribuyente())->getSheetConfig($idContEmisor);
        return view('configuracion/concepto/main')
                ->with('sheetName',$sheetCont->nombre_sheet_google);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCambiarImagenPdf() {
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $imgEmpresa=$contEmisor->rfc."/img_empresa.png";
        $exists = Storage::disk('ftp')->exists($imgEmpresa);
        $imgPdf =  "http://www.conuxi.com/images/factunx/img_empresa.jpg";
        if($exists){
            $imgPdf = "http://www.conuxi.com/images/factunx/".$contEmisor->rfc."/img_empresa.png";
        }
        return view('configuracion/imagen_pdf/main')
                ->with('urlImg',$imgPdf);
    }
    
    public function cambiarImagenPdf(Request $request) {
        $image = $request->file('photo_create');
        $name = $image->getClientOriginalName();
        $extensionOrg = $image->getClientOriginalExtension();
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        $userPadre=$usuarioContribuyente->getUsuarioByContribuyente($contEmisor->id_contribuyente);
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        //RFC.DIRECTORY_SEPARATOR.ARCHIVO
        $imageRealPath = $image->getRealPath();
        $img = Image::make($imageRealPath); // use this if you want facade style code
        $img->resize(300,200);
        Storage::disk('local')->makeDirectory($contEmisor->rfc);
        $img->save($storagePath.$contEmisor->rfc.DIRECTORY_SEPARATOR."img_empresa.png");
        //Storage::disk('local')->put($contEmisor->rfc.DIRECTORY_SEPARATOR.$name,$img);
        $contents=file_get_contents($storagePath.$contEmisor->rfc.DIRECTORY_SEPARATOR."img_empresa.png");
        Storage::disk('ftp')->put($contEmisor->rfc.DIRECTORY_SEPARATOR."img_empresa.png",$contents);
        //Storage::disk('local')->put($contEmisor->rfc.DIRECTORY_SEPARATOR.$name,$contents);
        $idArchvoOld=null;
		if($contEmisor->id_archivo_img_pdf!=null){
            $idArchvoOld=$contEmisor->id_archivo_img_pdf;
        }
        $archivo=new \App\Archivo();
        $arcImgPdf=$archivo->guardar($contents,$name,$extensionOrg);
        $usuarioContribuyente->actualizarArchivoImgPdf($userPadre->id,$contEmisor->id_contribuyente,$arcImgPdf->id);
        $imgPdf =  "http://www.conuxi.com/images/factunx/".$contEmisor->rfc."/img_empresa.png";
        //$imgPdf =  "http://www.conuxi.com/images/factunx/img_empresa.jpg";
        //Storage::disk('local')->deleteDirectory($contEmisor->rfc);
        if($idArchvoOld!=null){
            $archivo->borrar($idArchvoOld);
        }
        return view('configuracion/imagen_pdf/main')
                ->with('mensaje',"Se agrego correctamente")
                ->with('urlImg',$imgPdf);
    }
    
    public function indexSerieNumero() {
        return view('configuracion/serie_numero/main');
    }
    
    function crudSerieNumero(Request $request){
        $isEdit = $request->get('isEdit');
        $isRemove = $request->get('isRemove');
        $numero = $request->get('numero');
        $serie = $request->get('serie');
        $validator = Validator::make($request->all(), ['serie' => 'required','numero' => 'required|not_in:0']);
        if ($validator->fails()) { 
            return redirect('/configuracion/serie_numero/crud')->withErrors($validator)->withInput();
        }
        $idContEmisor= $this->getIdContEmisor();
        $serie=strtoupper($serie);
        $serNum=new \App\SerieNumero();
        if($isEdit!="-1"){
            $exist=$serNum->existeSerie($idContEmisor, $serie);
            if(!$exist->isEmpty()){
                return view('configuracion/serie_numero/main')->with('mensaje',"Existe Serie con nombre [".$serie."] no se puede modificar");
            }
            $serNum->updateById($isEdit, $serie, $numero);
            return view('configuracion/serie_numero/main')->with('mensaje',"Se actualizo correctamente");
        }
        if($isRemove!="-1"){
            $serNum->removeById($isRemove);
            return view('configuracion/serie_numero/main')->with('mensaje',"Se elimino correctamente");
        }
        $exist=$serNum->existeSerie($idContEmisor, $serie);
        if(!$exist->isEmpty()){
            return view('configuracion/serie_numero/main')->with('mensaje',"Existe Serie con nombre [".$serie."] no se puede agregar");
        }
        $serNum->agregar($idContEmisor, $serie, $numero);
        return view('configuracion/serie_numero/main')->with('mensaje',"Se guardo correctamente");
    }
    
    public function guardarConceptos(){
        $idContEmisor= $this->getIdContEmisor();
        $sheetCont=(new \App\SheetContribuyente())->getSheetConfig($idContEmisor);
        $sheet=$this->getSheetByGoogle($sheetCont->nombre_sheet_google);
        $prefijo=$this->getPrefijoByGoogle($sheetCont->nombre_sheet_google);
        $this->guardaConceptos($sheet,$prefijo);
        return view('configuracion/concepto/main')
                ->with('sheetName',$sheetCont->nombre_sheet_google)
                ->with('mensaje',"Se guardo correctamente los conceptos");
    }
    
    public function exportCodigoBarras(){
        $idContEmisor= $this->getIdContEmisor();
        $sheetCont=(new \App\SheetContribuyente())->getSheetConfig($idContEmisor);
        $sheet=$this->getSheetByGoogle($sheetCont->nombre_sheet_google);
        $prefijo=$this->getPrefijoByGoogle($sheetCont->nombre_sheet_google);
        $this->guardaConceptos($sheet,$prefijo);
        $concepto=new \App\Concepto();
        $vals=$concepto->getListaExport($idContEmisor);
        $this->updateCodigoBarra($sheet,$vals);
    }    
    
    private function getIdContEmisor(){
        $idUser=Auth::user()->id;
        $usuarioContribuyente=new \App\UsuarioContribuyente();
        $contEmisor=$usuarioContribuyente->getContribuyenteByUsuario($idUser);
        return $contEmisor->id;
    }
    
    private function guardaConceptos($sheet,$idPrefix){
        $values=$sheet->range('')->all();
        $lengArr=count($values);
        if($lengArr>0){
            $concepto=new \App\Concepto();
            $idContEmisor= $this->getIdContEmisor();
            $concepto->eliminarTodo($idContEmisor);
            for($x = 1; $x < $lengArr; $x++) {
                $value=$values[$x];
                $num=str_pad($x, 8, "0", STR_PAD_LEFT);
                $id=$idPrefix.$num;
                $idUnidad=$value[5];
                $idProdServ=$value[6];
                $codigoBarra = rand(5, 999999);
                $concepto->guardar($id,$value[1],$idUnidad,$value[2],$value[3],
                        $value[4],$idContEmisor,$codigoBarra,$idProdServ);
                //campo Codigo
                $value[0]=$id;
                $cell="A".($x+1);
                $sheet->range($cell)->update([$value]);
            }
        }
    }
    
    private function updateCodigoBarra($sheet,$valuesDb){
        $values=$sheet->all();$listExcel=array();$listRowHeight=array();$listPathBarCode=array();
        $arrlength=count($valuesDb);
        array_push($listExcel,array($values[0][0],$values[0][1],$values[0][2],$values[0][3],$values[0][4],
            $values[0][5],$values[0][6],$values[0][7]));
        Storage::disk('local')->deleteDirectory('barcode'.DIRECTORY_SEPARATOR.$valuesDb[0]->id_contribuyente.DIRECTORY_SEPARATOR);
        for($x = 0; $x < $arrlength; $x++) {
            $listRowHeight[($x+2)]=50;
            array_push($listExcel,array($valuesDb[$x]->id,$valuesDb[$x]->nombre,$valuesDb[$x]->precio_unitario,
            $valuesDb[$x]->predial,$valuesDb[$x]->identificacion,$valuesDb[$x]->unidad_nom));
            $id = $valuesDb[$x]->codigo_barra;
            $name = $id.'barCode.png';
            $barCode=DNS1D::getBarcodePNG($id, "C39+",2,50);
            $image = base64_decode($barCode);
            $listPathBarCode[$x]='barcode'.DIRECTORY_SEPARATOR.$valuesDb[$x]->id_contribuyente.DIRECTORY_SEPARATOR.$name;
            Storage::disk('local')->put($listPathBarCode[$x], $image);
        }
        $this->createExcelConceptos($valuesDb,$listExcel,$listRowHeight,$listPathBarCode);
    }

    private function createExcelConceptos($valuesDb,$listExcel,$listRowHeight,$listPathBarCode){
            Excel::create('Conceptos', function($excel) use ($valuesDb,$listExcel,$listRowHeight,$listPathBarCode) {
            $excel->setTitle('Conceptos Conuxi');
            $excel->setCreator('Armando Cordova')->setCompany('conuxi.com');
            $excel->setDescription('Descarga Codigo de Barras');
            $excel->sheet('Conceptos', function($sheet) use ($valuesDb,$listExcel,$listRowHeight,$listPathBarCode) {
                $arrlength=count($valuesDb);
                $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                for($x = 0; $x < $arrlength; $x++) {
                    $objDrawing = new \PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath($storagePath.$listPathBarCode[$x]); //your image path
                    $objDrawing->setCoordinates('H'.($x+2));
                    $objDrawing->setWorksheet($sheet);
                }
                $sheet->setWidth(array('A'=>15,'B'=>40,'C'=>10,'D'=>15,'E'=>15,'F'=>30,'G'=>20,'H'=>50));
                $sheet->setHeight($listRowHeight);
                $sheet->fromArray($listExcel, null, 'A1', false, false);
            });

        })->download('xlsx');
    }
    
    private function getPrefijoByGoogle($file) {
        try {
            $clientGoogle = new \Google_Client();
            $clientGoogle->setApplicationName("factunx");
            $clientGoogle->setScopes([\Google_Service_Sheets::DRIVE, \Google_Service_Sheets::SPREADSHEETS]);
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $clientGoogle->setAuthConfig($storagePath . "factunx-77709dce3cf6.json");
            $clientGoogle->setAccessType('online');
            $service = new \Google_Service_Sheets($clientGoogle);
            $sheets = new \GoogleSheets\Sheets();
            $sheets->setService($service);
            $sheet=$sheets->spreadsheet($file)->sheet('Identificador');
            $values=$sheet->all();
            return $values[1][0];
        } catch (Exception $ex) {
            
        }
    }    
    
    private function getSheetByGoogle($file) {
        try {
            $clientGoogle = new \Google_Client();
            $clientGoogle->setApplicationName("factunx");
            $clientGoogle->setScopes([\Google_Service_Sheets::DRIVE, \Google_Service_Sheets::SPREADSHEETS]);
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $clientGoogle->setAuthConfig($storagePath . "factunx-77709dce3cf6.json");
            $clientGoogle->setAccessType('online');
            $service = new \Google_Service_Sheets($clientGoogle);
            $sheets = new \GoogleSheets\Sheets();
            $sheets->setService($service);
            $sheet=$sheets->spreadsheet($file)->sheet('Conceptos');
            return $sheet;
        } catch (Exception $ex) {
            
        }
    }

}
