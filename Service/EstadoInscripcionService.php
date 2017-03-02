<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\EstadoInscripcion as Model;
use DB;

class EstadoInscripcionService extends BaseService{
	use ServiceCRUD;
    static $ESTADO_INGRESAR_REGISTRO = 2;
    static $ESTADO_CONFIRMAR_REGISTRO = 3;
    static $ESTADO_CARGA_DOCUMENTOS = 6;
    static $ESTADO_CONFIRMAR_CARGA_DOCUMENTOS = 7;
    static $ESTADO_VERIFICAR_CARGA_DOCUMENTOS = 8;
    static $ESTADO_APROBAR_DOCUMENTOS = 9;
    static $ESTADO_PAGO_INSCRIPCION = 10;
    static $ESTADOO_CONFIRMAR_INSCRIPCION = 11;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }
}