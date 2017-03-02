<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\Ubigeo as Model;
use DB;

class UbigeoService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }    
    function PaisArray(){
    	$PaisesArray = [];
    	$Paises = Model::select('UbigeoID','PaisID','Pais')->orderBy('Pais')->distinct()->get();
    	foreach($Paises as $Pais){
    		$PaisesArray[$Pais->PaisID] = $Pais;
    	}
    	return $PaisesArray;
    }
    function DepartamentoArray($PaisId){
    	$DepartamentosArray = [];
    	$Departamentos = Model::select('UbigeoID','DeptoID','Departamento')
                        ->where('PaisID',$PaisId)
                        ->orderBy('Departamento')
                        ->distinct()->get();
    	foreach($Departamentos as $Departamento){
    		$DepartamentosArray[$Departamento->DeptoID] = $Departamento;
    	}
    	return $DepartamentosArray;
    }
    function ProvinciaArray($PaisId,$DepartamentoId){
    	$ProvinciasArray = [];
    	$Provincias = Model::select('UbigeoID','ProvID','Provincia')->where('PaisID',$PaisId)->where('DeptoID',$DepartamentoId)->orderBy('Provincia')->distinct()->get();
    	foreach($Provincias as $Provincia){
    		$ProvinciasArray[$Provincia->ProvID] = $Provincia;
    	}
    	return $ProvinciasArray;
    }
    function DistritoArray($PaisId,$DepartamentoId,$ProvinciaId){
        $DistritosArray = [];
        $Distritos = Model::select('UbigeoID','DistID','Distrito')->where('PaisID',$PaisId)->where('DeptoID',$DepartamentoId)->orderBy('Distrito')->where('ProvID',$ProvinciaId)->distinct()->get();
        foreach($Distritos as $Distrito){
            $DistritosArray[$Distrito->DistID] = $Distrito;
        }
        return $DistritosArray;
    }
}
