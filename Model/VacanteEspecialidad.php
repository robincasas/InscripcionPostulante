<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class VacanteEspecialidad extends BaseModel{
    protected $table = 'VW_VACANTE_ESPECIALIDAD';
    protected $primaryKey = 'ID_PROCESO,ID_MODALIDAD,ID_FACULTAD,ID_ESPECIALIDAD';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','ID_ESPECIALIDAD','VACANTE']; 
    protected $visible = ['ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','ID_ESPECIALIDAD','VACANTE'];

    protected $informative = ['VACANTE'];

    protected $name = 'Vacantes por Especialidad';
    protected $primaryKeys = ['ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','ID_ESPECIALIDAD'];
    protected $labels = [
        'ID_PROCESO' => 'Id Proceso'
        ,'ID_MODALIDAD' => 'Id Modalidad'
        ,'ID_FACULTAD' =>'Id Facultad'
        ,'ID_ESPECIALIDAD' =>'Id Especialidad'
        ,'VACANTE'=>'Vacante'
    ];
 
}