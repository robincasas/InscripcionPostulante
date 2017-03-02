<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteDomicilio extends BaseModel{
    protected $table = 'TB_POSTULANTE_DOMICILIO';
    protected $primaryKey = 'ID_POSTULANTE';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','UBIGEO','ID_PAIS','ID_DEPARTAMENTO','ID_PROVINCIA','ID_DISTRITO','DIRECCION','TELEFONO','REFERENCIA','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA']; 
    protected $visible  = ['ID_POSTULANTE','UBIGEO','ID_PAIS','ID_DEPARTAMENTO','ID_PROVINCIA','ID_DISTRITO','DIRECCION','TELEFONO','REFERENCIA','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA'];

    protected $informative = ['UBIGEO'];

    protected $name = 'Datos de Nacimiento Postulante';
    protected $primaryKeys = ['ID_POSTULANTE'];
    protected $labels = [
        'ID_POSTULANTE' => 'Id Postulante'        
        ,'UBIGEO' => 'Ubigeo'
        ,'ID_PAIS' =>'Id País'
        ,'ID_DEPARTAMENTO'=>'Id Departamento'
        ,'ID_PROVICIA'=>'Id Provincia'
        ,'ID_DISTRITO' => 'Id Distrito'
        ,'DIRECCION' => 'Dirección'
        ,'TELEFONO' => 'Teléfono'
        ,'REFERENCIA' => 'Referencia'
    ];
 
}