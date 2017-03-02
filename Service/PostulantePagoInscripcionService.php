<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\PostulantePagoInscripcion as Model;
use DB;

class PostulantePagoInscripcionService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }
    function maxOrden($ID_POSTULANTE){
    	$max = Model::where('ID_POSTULANTE',$ID_POSTULANTE)->max('ORDEN');
    	if(!$max) return 0;
    	return $max;
    }
}