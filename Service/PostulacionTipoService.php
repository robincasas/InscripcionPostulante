<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\PostulacionTipo as Model;
use DB;

class PostulacionTipoService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }
    public static function listarActivos(){
    	return Model::where('ESTADO_POSTULACION_TIPO','>','0')->get();
    }
}