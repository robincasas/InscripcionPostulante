<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class Tipo extends BaseModel{
    protected $table = 'TB_TIPO';
    protected $primaryKey = 'ID_TIPO';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['CLAVE','CODIGO','DESCRIPCION','ORDEN','F_CREA','F_MODIFICA','USUARIO_CREA','USUARIO_MODIFICA','ACTIVO','TABLA']; 
    protected $visible = ['ID_TIPO','CODIGO','DESCRIPCION','ORDEN','F_CREA','F_MODIFICA','USUARIO_CREA','USUARIO_MODIFICA','ACTIVO','TABLA'];

    protected $informative = ['DESCRIPCION'];

    protected $name = 'Tipo';
    protected $primaryKeys = ['ID_TIPO'];
    protected $labels = [
        'ID_TIPO' => 'ID'
        ,'CODIGO' => 'Código'
        ,'DESCRIPCION' =>'Descripción'
        ,'ORDEN'=>'Orden'
        ,'F_CREA'=>'Fecha Creación'
        ,'F_MODIFICA' => 'Fecha Modificación'
        ,'USUARIO_CREA' => 'Usuario Crea'
        ,'USUARIO_MODIFICA'=>'Usuario Modifica'
        ,'ACTIVO'=>'Activa'
        ,'TABLA'=>'Tabla'
    ];
 
}