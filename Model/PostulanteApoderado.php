<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteApoderado extends BaseModel{
    protected $table = 'TB_POSTULANTE_APODERADO';
    protected $primaryKey = 'ID_POSTULANTE';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','PATERNO','MATERNO','NOMBRES','DNI','CORREO','TELEFONO','ID_PARENTESCO','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA']; 
    protected $visible  = ['ID_POSTULANTE','PATERNO','MATERNO','NOMBRES','DNI','CORREO','TELEFONO','ID_PARENTESCO','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA'];

    protected $informative = ['PATERNO','MATERNO','NOMBRES'];

    protected $name = 'Datos de Apoderado del Postulante';
    protected $primaryKeys = ['ID_POSTULANTE'];
    protected $labels = [
        'ID_POSTULANTE' => 'Id Postulante'        
        ,'PATERNO' => 'Apellido Paterno'
        ,'MATERNO' =>'Apellido Materno'
        ,'NOMBRES'=>'Nombres'
        ,'DNI'=>'DNI'
        ,'CORREO' => 'Correo'
        ,'TELEFONO' => 'Teléfono'
        ,'ID_PARENTESCO' => 'Id Parentesco'
    ];
 
}