<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulacionTipo extends BaseModel{
    protected $table = 'TB_POSTULACION_TIPO';
    protected $primaryKey = 'ID_POSTULACION_TIPO';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['NOMBRE_POSTULACION','ID_FLUJO','ESTADO_POSTULACION_TIPO','FECHA_HORA_BORRADO'];
    protected $visible = ['ID_POSTULACION_TIPO','NOMBRE_POSTULACION','ID_FLUJO','ESTADO_POSTULACION_TIPO','FECHA_HORA_BORRADO'];

    protected $informative = ['NOMBRE_POSTULACION'];

    protected $name = 'Tipo de Postulación';
    protected $primaryKeys = ['ID_POSTULACION_TIPO'];
    protected $labels = [
        'ID_POSTULACION_TIPO' => 'ID'
        ,'NOMBRE_POSTULACION' => 'Nombre'
        ,'ID_FLUJO' =>'Flujo ID'
        ,'ESTADO_POSTULACION_TIPO'=>'Estado'
        ,'FECHA_HORA_BORRADO'=>'Fecha Hora Borrado'      
    ];
 
}