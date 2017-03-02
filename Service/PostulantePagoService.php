<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\PostulantePago as Model;
use DB;

class PostulantePagoService extends BaseService{
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
    public function pagosPostulante($ID_POSTULANTE){
        $PostulantePago = new Model;

        return DB::table( $PostulantePago->getTable().' AS PP')
                 ->leftJoin('TB_CONCEPTO_PAGO AS CP','PP.ID_CONCEPTO_PAGO','=','CP.ID_CONCEPTO_PAGO')
                 ->where('PP.ID_POSTULANTE',$ID_POSTULANTE)
                 ->get()
                 ;
    }
}