<?php namespace App\Module\InscripcionPostulante\Model;

use DxsRavel\Essentials\Models\BaseModel;

class DocumentoModalidad extends BaseModel{
    protected $table = 'DocumentoModalidad';
    protected $primaryKey = 'DocumentoModalidadID';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['ModalidadID','DocumentoID','FechaCrea','FechaMod','Activo','UserCrea','UserMod']; 
    protected $visible = ['DocumentoModalidadID','ModalidadID','DocumentoID','FechaCrea','FechaMod','Activo','UserCrea','UserMod'];

    protected $informative = ['DocumentoID'];

    protected $name = 'Modalidad';
    protected $primaryKeys = ['DocumentoModalidadID'];
    protected $labels = [
        'DocumentoModalidadID' => 'ID'
        ,'ModalidadID' => 'Id Modalidad'
        ,'DocumentoID' =>'Id Documento'
        ,'FechaCrea'=>'Fecha Crea'
        ,'FechaMod'=>'Fecha Modifica'
        ,'Activo'=>'Activo'
        ,'UserCrea' => 'Fecha Modificación'
        ,'UserMod' => 'Usuario Crea'        
    ];
 
}