<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\PostulanteDocumento as Model;
use DB;

class PostulanteDocumentoService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }
    function maxOrden($ID_POSTULANTE,$ID_TIPO_DOCUMENTO){
    	$max = Model::where('ID_POSTULANTE',$ID_POSTULANTE)->where('ID_TIPO_DOCUMENTO',$ID_TIPO_DOCUMENTO)->max('ORDEN');
    	if(!$max) return 0;
    	return $max;
    }
    function totalPorTipoPostulante($ID_TIPO_DOCUMENTO,$ID_POSTULANTE){
        $count = Model::where('ID_POSTULANTE',$ID_POSTULANTE)->where('ID_TIPO_DOCUMENTO',$ID_TIPO_DOCUMENTO)->count();
        if(!$count) return 0;
        return $count;
    }
}