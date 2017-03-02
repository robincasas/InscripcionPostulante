<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\DocumentoModalidad as Model;
use DB;

class DocumentoModalidadService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }     
    public function listarDeModalidadArray($ModalidadesId){
    	$Arr = [];
    	$Q = Model::leftJoin('Documento','Documento.DocumentoID','=','DocumentoModalidad.DocumentoID')
    				  ->where('DocumentoModalidad.Activo',1);
    	$Q = $Q->where(function($query) use ($ModalidadesId){
					foreach($ModalidadesId as $ModalidadId){
						$query = $query->orWhere('ModalidadID',$ModalidadId);
					}
					return $query;
				});
    	$Documentos = $Q->select('DocumentoModalidad.DocumentoID','Documento.Descripcion')->orderBy('Descripcion')->get();
    	foreach($Documentos as $Documento){
    		$Arr[$Documento->DocumentoID] = $Documento->Descripcion;
    	}
    	return $Arr;
    }
    public function listarDeModalidad($ModalidadesId){
        $Arr = [];
        if(count($ModalidadesId)==0) return $Arr;
        $Q = Model::leftJoin('Documento','Documento.DocumentoID','=','DocumentoModalidad.DocumentoID')
                      ->where('DocumentoModalidad.Activo',1);
        $Q = $Q->where(function($query) use ($ModalidadesId){
                    foreach($ModalidadesId as $ModalidadId){
                        $query = $query->orWhere('ModalidadID',$ModalidadId);
                    }
                    return $query;
                });
        $Documentos = $Q->select('DocumentoModalidad.DocumentoID','Documento.*')->orderBy('Descripcion')->distinct()->get();       
        //return $Documentos;
        $Arr = [];
        foreach($Documentos as $Documento){
            /*
            $Arr[$Documento->DocumentoID] = ['DocumentoID' => $Documento->DocumentoID
                                            ,'Codigo' => $Documento->Codigo                                            
                                            ,'Descripcion' => $Documento->Descripcion
                                            //,'NOMBRE' => $Documento->Descripcion
                                            ,'Hojas' => (int) $Documento->Hojas
                                            ];
            */
            $Doc = new \stdClass;
            $Doc->DocumentoID = $Documento->DocumentoID;
            $Doc->Codigo = $Documento->Codigo;
            $Doc->Descripcion = $Documento->Descripcion;
            $Doc->Hojas = (int) $Documento->Hojas;
            $Arr[] = $Doc;
        }
        return $Arr;
    }
}
