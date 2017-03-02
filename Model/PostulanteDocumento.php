<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class PostulanteDocumento extends BaseModel{
    protected $table = 'TB_POSTULANTE_DOCUMENTO';
    protected $primaryKey = 'ID_POSTULANTE_DOCUMENTO';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ID_POSTULANTE','ID_TIPO_DOCUMENTO','ORDEN','NOMBRE','EXTENSION','PESO','RUTA','FH_CREA','USUARIO_CREA'];
    protected $visible  = ['ID_POSTULANTE_DOCUMENTO','ID_POSTULANTE','ID_TIPO_DOCUMENTO','ORDEN','NOMBRE','EXTENSION','PESO','RUTA','FH_CREA','USUARIO_CREA'];

    protected $informative = ['NOMBRE'];

    protected $name = 'Datos de Nacimiento Postulante';
    protected $primaryKeys = ['ID_POSTULANTE_DOCUMENTO'];
    protected $labels = [
        'ID_POSTULANTE_DOCUMENTO' => 'Id Postulante'        
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