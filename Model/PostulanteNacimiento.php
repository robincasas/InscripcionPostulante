<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteNacimiento extends BaseModel{
    protected $table = 'TB_POSTULANTE_NACIMIENTO';
    protected $primaryKey = 'ID_POSTULANTE';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','F_NACIMIENTO','UBIGEO','ID_PAIS','ID_DEPARTAMENTO','ID_PROVINCIA','ID_DISTRITO','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA']; 
    protected $visible  = ['ID_POSTULANTE','F_NACIMIENTO','UBIGEO','ID_PAIS','ID_DEPARTAMENTO','ID_PROVINCIA','ID_DISTRITO','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA'];

    protected $informative = ['UBIGEO'];

    protected $name = 'Datos de Nacimiento Postulante';
    protected $primaryKeys = ['ID_POSTULANTE'];
    protected $labels = [
        'ID_POSTULANTE' => 'Id Postulante'
        ,'F_NACIMIENTO' => 'Fecha Nacimiento'
        ,'UBIGEO' => 'Ubigeo'
        ,'ID_PAIS' =>'Id País'
        ,'ID_DEPARTAMENTO'=>'Id Departamento'
        ,'ID_PROVICIA'=>'Id Provincia'
        ,'ID_DISTRITO' => 'Id Distrito'
    ];
 
}