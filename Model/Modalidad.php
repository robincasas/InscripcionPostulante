<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class Modalidad extends BaseModel{
    protected $table = 'Modalidad';
    protected $primaryKey = 'ModalidadID';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['Codigo','Descripcion','FechaCrea','FechaMod','Activo','UserCrea','UserMod']; 
    protected $visible = ['ModalidadID','Codigo','Descripcion','FechaCrea','FechaMod','Activo','UserCrea','UserMod'];

    protected $informative = ['Descripcion'];

    protected $name = 'Modalidad';
    protected $primaryKeys = ['ModalidadID'];
    protected $labels = [
        'ModalidadID' => 'ID'
        ,'Codigo' => 'Código'
        ,'Descripcion' =>'Descripción'
        ,'FechaCrea'=>'Orden'
        ,'Activo'=>'Fecha Creación'
        ,'UserCrea' => 'Fecha Modificación'
        ,'UserMod' => 'Usuario Crea'        
    ];
 
}