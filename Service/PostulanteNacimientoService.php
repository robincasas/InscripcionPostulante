<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\PostulanteNacimiento as Model;
use DB;

class PostulanteNacimientoService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }     
}