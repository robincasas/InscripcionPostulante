<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulantePago extends BaseModel{
    protected $table = 'TB_POSTULANTE_PAGO';
    protected $primaryKey = 'ID_POSTULANTE_PAGO';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_MODALIDAD_CONCEPTO_PAGO','ID_POSTULANTE','MONTO'];
    protected $visible  = ['ID_POSTULANTE_PAGO','ID_MODALIDAD_CONCEPTO_PAGO','ID_POSTULANTE','MONTO'];

    protected $informative = ['ID_MODALIDAD_CONCEPTO_PAGO'];

    protected $name = 'Postulante Pago';
    protected $primaryKeys = ['ID_POSTULANTE_PAGO'];
    protected $labels = [];
 
}