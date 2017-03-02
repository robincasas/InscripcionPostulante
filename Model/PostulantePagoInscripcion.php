<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulantePagoInscripcion extends BaseModel{
    protected $table = 'TB_POSTULANTE_PAGO_INSCRIPCION';
    protected $primaryKey = 'ID_POSTULANTE_PAGO';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','ORDEN','NOMBRE','EXTENSION','PESO','RUTA','FH_CREA','USUARIO_CREA'];
    protected $visible  = ['ID_POSTULANTE_DOCUMENTO','ID_POSTULANTE','ORDEN','NOMBRE','EXTENSION','PESO','RUTA','FH_CREA','USUARIO_CREA'];

    protected $informative = ['NOMBRE'];

    protected $name = 'Postulante Pago Inscripción';
    protected $primaryKeys = ['ID_POSTULANTE_PAGO'];
    protected $labels = [];
 
}