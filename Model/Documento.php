<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class Documento extends BaseModel{
    protected $table = 'Documento';
    protected $primaryKey = 'DocumentoID';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['Codigo','Descripcion','FechaCrea','FechaMod','Activo','UserCrea','UserMod','Hojas']; 
    protected $visible = ['DocumentoID','Codigo','Descripcion','FechaCrea','FechaMod','Activo','UserCrea','UserMod','Hojas'];

    protected $informative = ['Descripcion'];

    protected $name = 'Modalidad';
    protected $primaryKeys = ['DocumentoID'];
    protected $labels = [  
    ];
 
}