<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class Ubigeo extends BaseModel{
    protected $table = 'UBIGEO';
    protected $primaryKey = 'UbigeoID';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['PaisID','DeptoID','ProvID','DistID','Pais','Departamento','Provincia','Distrito','Activo','UserCrea','UserMod']; 
    protected $visible = ['UbigeoID','PaisID','DeptoID','ProvID','DistID','Pais','Departamento','Provincia','Distrito','Activo','UserCrea','UserMod'];

    protected $informative = ['Pais','Departamento','Provincia','Distrito'];

    protected $name = 'Ubigeo';
    protected $primaryKeys = ['UbigeoID'];
    protected $labels = [
        'UbigeoID' => 'Id'
        ,'PaisID' => 'Id País'
        ,'DeptoID' =>'Id Departamento'
        ,'ProvID'=>'Id Provincia'
        ,'DistID'=>'Id Distrito'
        ,'Pais' => 'País'
        ,'Departamento' => 'Departamento'
        ,'Distrito' => 'Distrito'
        ,'Activo' => 'Activo'
        ,'UserCrea' => 'Usuario Crea'
        ,'UserMod' => 'Usuario Modifica'
    ];
 
}