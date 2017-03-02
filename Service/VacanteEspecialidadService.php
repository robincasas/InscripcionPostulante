<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\VacanteEspecialidad as Model;
use DB;
use stdClass;

class VacanteEspecialidadService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }     
    function listarConVacantes($ID_PROCESO,$ID_MODALIDAD,$ID_FACULTAD){
        $EspecialidadLista = [];
    	$Especialidades = Model::leftJoin('Especialidad','EspecialidadID','=','ID_ESPECIALIDAD')
    				->select('EspecialidadID','Descripcion','Codigo')
    				->where('ID_PROCESO',$ID_PROCESO)
    				->where('ID_MODALIDAD',$ID_MODALIDAD)
                    ->where('ID_FACULTAD',$ID_FACULTAD)
    				->where('VACANTE','>',0)
                    ->get();
        foreach($Especialidades as $Especialidad){
            $Esp = new stdClass;
            $Esp->EspecialidadID = $Especialidad->EspecialidadID;
            $Esp->Descripcion = $Especialidad->Descripcion;
            array_push($EspecialidadLista,$Esp);
        }
        return $EspecialidadLista;
    }
}