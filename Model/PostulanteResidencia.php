<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteResidencia extends BaseModel{
    protected $table = 'TB_POSTULANTE_RESIDENCIA';
    protected $primaryKey = 'ID_POSTULANTE';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','ID_UBIGEO','ID_DISTRITO','DIRECCION','TELEFONO','REFERENCIA','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA']; 
    protected $visible  = ['ID_POSTULANTE','ID_UBIGEO','ID_DISTRITO','DIRECCION','TELEFONO','REFERENCIA','FH_CREA','FH_ACTUALIZA','USUARIO_CREA','USUARIO_ACTUALIZA'];

    protected $informative = ['TELEFONO'];

    protected $name = 'Datos de Nacimiento Postulante';
    protected $primaryKeys = ['ID_POSTULANTE'];
    protected $labels = [
        'ID_POSTULANTE' => 'Id Postulante'                            
        ,'ID_DISTRITO' => 'Id Distrito'
        ,'DIRECCION' => 'Dirección'
        ,'TELEFONO' => 'Teléfono'
        ,'REFERENCIA' => 'Referencia'
    ];
 
}