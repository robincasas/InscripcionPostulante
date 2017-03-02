<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\Tipo as Model;
use DB;

class TipoService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }    
    public static function listarTabla($tabla){
    	return Model::where('ACTIVO',1)->where('TABLA',$tabla)->get();
    }
    public function listarTablaArray($tabla,$dummy = false){
    	return $this->listarArray(['ACTIVO'=>1,'TABLA'=>$tabla],$dummy,['ORDEN'=>'ASC']);  	 
    }   
}