<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\VacanteFacultad as Model;
use DB;
use stdClass;

class VacanteFacultadService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }     
    function listarConVacantes($ID_PROCESO,$ID_MODALIDAD = false){
    	$FacultadesLista = [];
        if($ID_MODALIDAD){
            $Facultades = Model::leftJoin('Facultad','FacultadID','=','ID_FACULTAD')
                        ->select('FacultadID','Descripcion','Codigo','Sigla')
                        ->where('ID_PROCESO',$ID_PROCESO)
                        ->where('ID_MODALIDAD',$ID_MODALIDAD)
                        ->where('VACANTE','>',0)
                        ->distinct()
                        ->get();
        }else{
            $Facultades = Model::leftJoin('Facultad','FacultadID','=','ID_FACULTAD')
                    ->select('FacultadID','Descripcion','Codigo','Sigla')
                    ->where('ID_PROCESO',$ID_PROCESO)
                    //->where('ID_MODALIDAD',$ID_MODALIDAD)
                    ->where('VACANTE','>',0)
                    ->distinct()
                    ->get();
        }
        foreach($Facultades as $Facultad){
            $Fac = new stdClass;
            $Fac->FacultadID = $Facultad->FacultadID;
            $Fac->Descripcion = $Facultad->Descripcion;
            array_push($FacultadesLista,$Fac);
        }
        return $FacultadesLista;
    }
    function listarModalidadesConVacantes($ID_PROCESO,$ID_FACULTAD){
        $ModalidadesLista = [];
        $Modalidades = Model::leftJoin('Modalidad','ModalidadID','=','ID_MODALIDAD')
                    ->select('ModalidadID','Descripcion','Codigo','Tipo','Multiple')
                    ->where('ID_PROCESO',$ID_PROCESO)
                    //->where('ID_MODALIDAD',$ID_MODALIDAD)
                    ->where('ID_FACULTAD',$ID_FACULTAD)
                    ->where('VACANTE','>',0)
			->where('Activo','1')
                    ->distinct()
                    ->get();
        foreach($Modalidades as $Modalidad){
            $Mod = new stdClass;
            $Mod->ModalidadID = $Modalidad->ModalidadID;
            $Mod->Descripcion = $Modalidad->Descripcion;
            $Mod->Tipo = $Modalidad->Tipo;
            $Mod->Multiple = $Modalidad->Multiple;
            array_push($ModalidadesLista,$Mod);
        }
        return $ModalidadesLista;
    }

    public function listarConVacantesArray($ID_PROCESO,$ID_MODALIDAD){
        $FacultadesArray = [];
        $Facultades = Model::leftJoin('Facultad','FacultadID','=','ID_FACULTAD')
                    ->select('FacultadID','Descripcion','Codigo','Sigla')
                    ->where('ID_PROCESO',$ID_PROCESO)
                    ->where('ID_MODALIDAD',$ID_MODALIDAD)
                    ->where('VACANTE','>',0)
                    ->distinct()
                    ->get();
        foreach($Facultades as $Facultad){
            //$Facultad->Descripcion =  utf8_encode($Facultad->Descripcion);
            $FacultadesArray[$Facultad->FacultadID] = ['FacultadID'=>$Facultad->FacultadID,'Descripcion'=>$Facultad->Descripcion];
        }
        return $FacultadesArray;
    }
}
