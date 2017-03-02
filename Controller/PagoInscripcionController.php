<?php
namespace App\Module\InscripcionPostulante\Controller;

use DxsRavel\Essentials\Controllers\BaseController;
use Input;
use Auth;
use Validator;
use Response;
use File;
use DB;

use App\Module\AccesoPostulante\Service\PostulanteService;
use App\Module\InscripcionPostulante\Service\TipoService;
use App\Module\InscripcionPostulante\Service\PostulantePagoInscripcionService;
use App\Module\InscripcionPostulante\Service\EstadoInscripcionService;

use App\Module\RevisionPostulante\Service\PostulantePagoInscripcionEstadoService;
use App\Module\RevisionPostulante\Service\DocumentoRevisionService;

class PagoInscripcionController extends BaseController{

	public function guardar(){
		$this->response['input'] = Input::all();

		$input = Input::all();
		$this->response['input'] = Input::all();
		$new = Input::get('new');

		$rules = ['imagen' => 'image|max:3000'];
		$validation = Validator::make($input, $rules);				
		$PostulantePagoInscripcionService = new PostulantePagoInscripcionService;
		$PostulantePagoInscripcionEstadoService = new PostulantePagoInscripcionEstadoService;		

		$Postulante = PostulanteService::postulanteAuth();
		
		if ($validation->fails()){
			//return Response::make($validation->errors->first(), 400);
			$this->response['error'] = true;
			$this->setResponseTitle('ERROR: ARCHIVO NO SOPORTADO');
			$this->toJSON();
		}else{			
			
			$old['ID_POSTULANTE'] = $Postulante->ID_POSTULANTE;		
			//$new2['ESTADO_INSCRIPCION'] = EstadoInscripcionService::$ESTADO_CARGA_DOCUMENTOS;
			$new2['FH_ACTUALIZA'] = $this->datetime();
			$PostulanteService = new PostulanteService;
			$rs = $PostulanteService->editar($old,$new2);

			$File = Input::file('imagen');

			$new['ID_POSTULANTE'] = $Postulante->ID_POSTULANTE;
			$new['NOMBRE'] = $File->getClientOriginalName();
	        $new['EXTENSION'] = $File->getClientOriginalExtension();

			$MAX_ORDEN = $PostulantePagoInscripcionService->maxOrden( $new['ID_POSTULANTE'] );
			$ORDEN = $MAX_ORDEN + 1;
			$Raiz = env('DIRECTORIO_PAGOS','');			
			$FileName = ($Postulante->ID_PROCESO) .'-'. ($new['ID_POSTULANTE'])  .'-'. ($ORDEN) .'.'. $new['EXTENSION'];			
			$Ruta =  $Raiz . $FileName;
			$new['RUTA'] = $Ruta;
			$new['PESO'] = $File->getClientSize();
			$new['ORDEN'] = $ORDEN;
			$new['FH_CREA'] = $this->datetime();			

			$PostulantePagoInscripcionService->agregar($new);

			$new3['USUARIO_ULTIMA_DECISION'] = DB::Raw('NULL');
			$new3['FH_ULTIMA_REVISION'] = DB::Raw('NULL');
			$new3['DECISION_ULTIMA_REVISION'] = -2;
			$new3['COMENTARIO_ULTIMA_REVISION'] = DB::Raw('NULL');
			$PostulantePagoInscripcionEstadoService->editarOcrear($old,$new3);
	        	       
	        $upload_success = $File->move($Raiz, $FileName);
	        
	        $upload_success = true;

	        if( $upload_success ) {
	        	//return Response::json('success', 200);
	        	$PagoLista = $PostulantePagoInscripcionService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
				$this->response['Pagos'] = $PagoLista;

	        	$this->response['error'] = false;
	        	$this->setResponseTitle('ARCHIVO ALMACENADO CORRECTAMENTE');
	        } else {
	        	$this->response['error'] = true;
	        	$this->setResponseTitle('ERROR AL ALMACENAR EL ARCHIVO');
	        	//return Response::json('error', 400);
	        }
    	}

		$this->toJSON();
	}

	function borrar(){
		$ID_POSTULANTE = Input::get('ID_POSTULANTE');
		$ID_POSTULANTE_PAGO = Input::get('ID_POSTULANTE_PAGO');
		$this->response['input'] = Input::all();

		$Postulante = PostulanteService::postulanteAuth();
		/*
		$old = ['ID_POSTULANTE' => $Postulante->ID_POSTULANTE];
		$new = [
				'ESTADO_INSCRIPCION' => EstadoInscripcionService::$ESTADO_CARGA_DOCUMENTOS 
				,'FH_ACTUALIZA' => $this->datetime()
				];
		$PostulanteService = new PostulanteService;
		$PostulanteService->editar($old,$new);
		*/

		$PostulantePagoInscripcionService = new PostulantePagoInscripcionService;
		$Pago = $PostulantePagoInscripcionService->existe(['ID_POSTULANTE_PAGO'=>$ID_POSTULANTE_PAGO]);
		//$Documentos = $PostulanteDocumentoService->listar(['ID_POSTULANTE_DOCUMENTO'=>$ID_POSTULANTE_DOCUMENTO,'ID_POSTULANTE'=>$ID_POSTULANTE]);
		if( File::delete($Pago->RUTA) ){
			$rs = $PostulantePagoInscripcionService->borrarFisico(['ID_POSTULANTE_PAGO'=>$ID_POSTULANTE_PAGO]);
			$this->response['error'] = !$rs;
			if($rs){
				$this->setResponseTitle('ARCHIVO BORRADO CORRECTAMENTE');
			}else{
				$this->setResponseTitle('ERROR AL GUARDAR CAMBIOS');
			}			
		}else{
			$this->response['error'] = true;
			$this->setResponseTitle('ERROR AL BORRAR ARCHIVO');
		}

		$this->toJSON();
	}
	
	public function finalizar(){
		$PostulantePagoInscripcionEstadoService = new PostulantePagoInscripcionEstadoService;
		$Postulante = PostulanteService::postulanteAuth();

		$old['ID_POSTULANTE'] = $Postulante->ID_POSTULANTE;

		$new3['USUARIO_ULTIMA_DECISION'] = DB::Raw('NULL');
		$new3['FH_ULTIMA_REVISION'] = DB::Raw('NULL');
		$new3['DECISION_ULTIMA_REVISION'] = 0;
		$new3['COMENTARIO_ULTIMA_REVISION'] = DB::Raw('NULL');

		$rs = $PostulantePagoInscripcionEstadoService->editar($old,$new3);
		
		$this->response['error'] = !$rs;
		if($rs){
			$this->setResponseTitle('TUS ARCHIVOS HAN PASADO A REVISION');
		}else{
			$this->setResponseTitle('ERROR: AL GUARDAR CAMBIOS');
		}
		$this->toJSON();
	}

	public function cancelar(){
		$PostulantePagoInscripcionEstadoService = new PostulantePagoInscripcionEstadoService;
		$Postulante = PostulanteService::postulanteAuth();

		$PagoEstado = $PostulantePagoInscripcionEstadoService->existe(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		if($PagoEstado->DECISION_ULTIMA_REVISION > 0){
			$this->response['error'] = true;
			$this->setResponseTitle('ERROR AL CANCELAR');
			$this->setResponseMessage('NO PUEDES CANCELAR CUANDO ESTA EN REVISIÓN');
			return $this->toJSON();
		}

		$old['ID_POSTULANTE'] = $Postulante->ID_POSTULANTE;

		$new3['USUARIO_ULTIMA_DECISION'] = DB::Raw('NULL');
		$new3['FH_ULTIMA_REVISION'] = DB::Raw('NULL');
		$new3['DECISION_ULTIMA_REVISION'] = -2;
		$new3['COMENTARIO_ULTIMA_REVISION'] = DB::Raw('NULL');

		$rs = $PostulantePagoInscripcionEstadoService->editar($old,$new3);
		
		$this->response['error'] = !$rs;
		if($rs){
			$this->setResponseTitle('TUS ARCHIVOS SE HAN QUITADO DE REVISION');
		}else{
			$this->setResponseTitle('ERROR: AL GUARDAR CAMBIOS');
		}
		$this->toJSON();
	}

	
}