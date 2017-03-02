<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class EstadoInscripcion extends BaseModel{
    protected $table = 'TB_ESTADO_INSCRIPCION';
    protected $primaryKey = 'ID_ESTADO';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_ESTADO','NOMBRE_ESTADO','PUEDE_CANCELAR','RUTA_CANCELAR'];
    protected $visible  = ['ID_ESTADO','NOMBRE_ESTADO','PUEDE_CANCELAR','RUTA_CANCELAR'];

    protected $informative = ['NOMBRE_ESTADO'];

    protected $name = 'Estado Inscripción';
    protected $primaryKeys = ['ID_ESTADO'];
    protected $labels = [
        'ID_ESTADO' => 'ID'
        ,'NOMBRE_ESTADO' => 'Nombre'
        ,'PUEDE_CANCELAR' =>'Puede Cancelar'
        ,'RUTA_CANCELAR'=>'Ruta'
    ];
 
}