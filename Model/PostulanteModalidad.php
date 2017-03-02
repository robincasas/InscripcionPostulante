<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteModalidad extends BaseModel{
    protected $table = 'TB_POSTULANTE_MODALIDAD';
    protected $primaryKey = 'ID_POSTULANTE,PRIORIDAD';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','PRIORIDAD','ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','ID_ESPECIALIDAD_1','ID_ESPECIALIDAD_2','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA']; 
    protected $visible  = ['ID_POSTULANTE','PRIORIDAD','ID_PROCESO','ID_MODALIDAD','ID_FACULTAD','ID_ESPECIALIDAD_1','ID_ESPECIALIDAD_2','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA'];

    protected $informative = ['ID_FACULTAD','ID_ESPECIALIDAD_1'];

    protected $name = 'Datos de Apoderado del Postulante';
    protected $primaryKeys = ['ID_POSTULANTE','PRIORIDAD'];
    protected $labels = [
        'ID_POSTULANTE' => 'Id Postulante'        
        ,'PRIORIDAD' => 'Prioridad'
        ,'ID_PROCESO' =>'Id Proceso'
        ,'ID_MODALIDAD'=>'Id Modalidad'
        ,'ID_FACULTAD'=>'Id Facultad'
        ,'ID_ESPECIALIDAD_1' => 'Id Especialidad 1'
        ,'ID_ESPECIALIDAD_2' => 'Id Especialidad 2'
    ];
 
}