<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class VacanteFacultad extends BaseModel{
    protected $table = 'VW_VACANTE_FACULTAD';
    protected $primaryKey = 'ID_PROCESO,ID_MODALIDAD,ID_FACULTAD';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','VACANTE']; 
    protected $visible = ['ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','VACANTE'];

    protected $informative = ['VACANTE'];

    protected $name = 'Vacantes por Facultad';
    protected $primaryKeys = ['ID_PROCESO','ID_MODALIDAD','ID_FACULTAD'];
    protected $labels = [
        'ID_PROCESO' => 'Id Proceso'
        ,'ID_MODALIDAD' => 'Id Modalidad'
        ,'ID_FACULTAD' =>'Id Facultad'
        ,'VACANTE'=>'Vacante'
    ];
 
}