<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteEncuesta extends BaseModel{
    protected $table = 'TB_POSTULANTE_ENCUESTA';
    protected $primaryKey = 'ID_POSTULANTE';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','ID_RAZON','ID_PREPARACION','ID_ANOS','ID_MESES','ID_ACADEMIA','ID_VECES','ID_RENUNCIA','ID_INGRESO_FAMILIAR','ID_MEDIO','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA']; 
    protected $visible  = ['ID_POSTULANTE','ID_RAZON','ID_PREPARACION','ID_ANOS','ID_MESES','ID_ACADEMIA','ID_VECES','ID_RENUNCIA','ID_INGRESO_FAMILIAR','ID_MEDIO','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA'];

    protected $informative = ['PATERNO','MATERNO','NOMBRES'];

    protected $name = 'Datos de Apoderado del Postulante';
    protected $primaryKeys = ['ID_POSTULANTE'];
    protected $labels = [
        'ID_POSTULANTE' => 'Id Postulante'        
        ,'ID_RAZON' => 'Razon'
        ,'ID_PREPARACION' =>'Preparación'
        ,'ID_ANOS'=>'Años Preparación'
        ,'ID_MESES'=> 'Meses Preparación'
        ,'ID_ACADEMIA' => 'Academia'
        ,'ID_RENUNCIA' => 'Veces Renuncia'
        ,'ID_INGRESO_FAMILIAR' => 'Ingreso Familiar'
        ,'ID_MEDIO' => 'Medio de Información'
    ];
 
}