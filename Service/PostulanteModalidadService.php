<?php namespace App\Module\InscripcionPostulante\Service;

use DxsRavel\Essentials\Services\BaseService;
use DxsRavel\Essentials\Traits\ServiceCRUD;

use App\Module\InscripcionPostulante\Model\PostulanteModalidad as Model;
use DB;

class PostulanteModalidadService extends BaseService{
	use ServiceCRUD;

    protected $Model;
    function __construct(){
        $this->Model = new Model;
    }     

    public function listarVerificacionDocumentos($ID_MODALIDAD,$TAKE,$PAGE = 1){
        $Date = new \DateTime();
        $tolerancia = env('PERIODO_REVISON','PT0M');
        $Date->sub( new \DateInterval($tolerancia) );        
        $FechaHoraLimite = $Date->format('Y-m-d H:i:s');

        $ESTADO_CONFIRMAR_CARGA_DOCUMENTOS = EstadoInscripcionService::$ESTADO_CONFIRMAR_CARGA_DOCUMENTOS;
        $ESTADO_VERIFICAR_CARGA_DOCUMENTOS = EstadoInscripcionService::$ESTADO_VERIFICAR_CARGA_DOCUMENTOS;

        $Postulantes = Model::leftJoin('TB_POSTULANTE','TB_POSTULANTE_MODALIDAD.ID_POSTULANTE','=','TB_POSTULANTE.ID_POSTULANTE')
        			->where('ID_MODALIDAD','=',$ID_MODALIDAD)
        			->where(function($query) use ($ESTADO_VERIFICAR_CARGA_DOCUMENTOS,$FechaHoraLimite){
        				return $query->where('ESTADO_INSCRIPCION','=',EstadoInscripcionService::$ESTADO_CONFIRMAR_CARGA_DOCUMENTOS)
				                    ->orWhere(function($query) use ($ESTADO_VERIFICAR_CARGA_DOCUMENTOS,$FechaHoraLimite){
				                        return $query->where('ESTADO_INSCRIPCION','=',$ESTADO_VERIFICAR_CARGA_DOCUMENTOS)
				                                     ->where('FH_ULTIMA_REVISION','<',$FechaHoraLimite)
				                                     ;
                    				});
        			})        	
        			->select('TB_POSTULANTE_MODALIDAD.ID_POSTULANTE','PATERNO','MATERNO','NOMBRES','CELULAR','CORREO')
        			//->select('TB_POSTULANTE.*')
        			->addSelect('TB_POSTULANTE.FH_ACTUALIZA')
        			->addSelect('TB_POSTULANTE.ESTADO_INSCRIPCION')
        			->addSelect('TB_POSTULANTE.USUARIO_ULTIMA_REVISION')
        			->addSelect('TB_POSTULANTE.FH_ULTIMA_REVISION')
                    ->distinct()
                    ->skip($TAKE*($PAGE-1))->take($TAKE)
                    ->orderBy('TB_POSTULANTE.FH_ACTUALIZA','DESC')
                    ->get();
        
        $PostulanteLista = [];
        foreach($Postulantes as $Postulante){        	
        	$Post = new \stdClass;
        	$Post->ID_POSTULANTE = $Postulante->ID_POSTULANTE;
        	$Post->PATERNO = $Postulante->PATERNO;
        	$Post->MATERNO = $Postulante->MATERNO;
        	$Post->NOMBRES = $Postulante->NOMBRES;
        	$Post->CELULAR = $Postulante->CELULAR;
        	$Post->CORREO = $Postulante->CORREO;
        	$Post->FH_ACTUALIZA = $Postulante->FH_ACTUALIZA;
        	$Post->ESTADO_INSCRIPCION = $Postulante->ESTADO_INSCRIPCION;
        	$Post->USUARIO_ULTIMA_REVISION = $Postulante->USUARIO_ULTIMA_REVISION;
        	$Post->FH_ULTIMA_REVISION = $Postulante->FH_ULTIMA_REVISION;
        	
        	$PostulanteLista[] = $Post;
        }
        return $PostulanteLista;
    }
    public function listarVerificacionDocumentosTotal($ID_MODALIDAD){
    	$Date = new \DateTime();
        $tolerancia = env('PERIODO_REVISON','PT0M');
        $Date->sub( new \DateInterval($tolerancia) );        
        $FechaHoraLimite = $Date->format('Y-m-d H:i:s');

        $ESTADO_CONFIRMAR_CARGA_DOCUMENTOS = EstadoInscripcionService::$ESTADO_CONFIRMAR_CARGA_DOCUMENTOS;
        $ESTADO_VERIFICAR_CARGA_DOCUMENTOS = EstadoInscripcionService::$ESTADO_VERIFICAR_CARGA_DOCUMENTOS;

        return Model::leftJoin('TB_POSTULANTE','TB_POSTULANTE_MODALIDAD.ID_POSTULANTE','=','TB_POSTULANTE.ID_POSTULANTE')
        			->where('ID_MODALIDAD','=',$ID_MODALIDAD)
        			->where(function($query) use ($ESTADO_VERIFICAR_CARGA_DOCUMENTOS,$FechaHoraLimite){
        				return $query->where('ESTADO_INSCRIPCION','=',EstadoInscripcionService::$ESTADO_CONFIRMAR_CARGA_DOCUMENTOS)
				                    ->orWhere(function($query) use ($ESTADO_VERIFICAR_CARGA_DOCUMENTOS,$FechaHoraLimite){
				                        return $query->where('ESTADO_INSCRIPCION','=',$ESTADO_VERIFICAR_CARGA_DOCUMENTOS)
				                                     ->where('FH_ULTIMA_REVISION','<',$FechaHoraLimite)
				                                     ;
                    				});
        			})        			
                    ->distinct()
                    //->skip($TAKE*($PAGE-1))->take($TAKE)
                    //->orderBy('FH_ACTUALIZA','DESC')
                    ->count();
    }
}