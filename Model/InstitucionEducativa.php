<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class InstitucionEducativa extends BaseModel{
    protected $table = 'InstitucionEducativa';
    protected $primaryKey = 'InstitucionEducativaID';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['CodigoLocal','Nombre','Nivel','Modalidad','Dependencia','Direccion','Director','Email','Telefono','Tipo','FechaCrea','FechaMod','Activo','UserCrea','UserMod']; 
    protected $visible = ['InstitucionEducativaID','CodigoLocal','Nombre','Nivel','Modalidad','Dependencia','Direccion','Director','Email','Telefono','Tipo','FechaCrea','FechaMod','Activo','UserCrea','UserMod'];

    protected $informative = ['Nombre'];

    protected $name = 'Institución Educativa';
    protected $primaryKeys = ['InstitucionEducativaID'];
    protected $labels = [
        'InstitucionEducativaID' => 'Id Institución Educativa'
        ,'CodigoLocal' => 'Código Local'
        ,'Nombre' => 'Nombre'
        ,'Nivel' => 'Nivel'
        ,'Modalidad'=>'Modalidad'
        ,'Direccion' => 'Dirección'
        ,'Director' => 'Director'
        ,'Email' => 'Email'
        ,'Telefono'=>'Teléfono'
        ,'Tipo' => 'Tipo'
        ,'FechaCrea' => 'Fecha Creación'
        ,'FechaMod' => 'Fecha Modificación'
        ,'Activo' => 'Activo'
        ,'UserCrea' => 'Usuario Crea'
        ,'UserMod' => 'Usuario Modifica'
    ];
 
}