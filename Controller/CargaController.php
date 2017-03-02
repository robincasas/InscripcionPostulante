<?php
namespace App\Module\InscripcionPostulante\Controller;
use DxsRavel\Essentials\Controllers\BaseController;
use Input;
use Auth;
use Validator;
use Response;
use File;

use App\Module\AccesoPostulante\Service\PostulanteService;
use App\Module\InscripcionPostulante\Service\TipoService;
use App\Module\InscripcionPostulante\Service\PostulanteModalidadService;
use App\Module\InscripcionPostulante\Service\DocumentoService;
use App\Module\InscripcionPostulante\Service\DocumentoModalidadService;
use App\Module\InscripcionPostulante\Service\PostulanteDocumentoService;
use App\Module\InscripcionPostulante\Service\EstadoInscripcionService;

use App\Module\RevisionPostulante\Service\DocumentoRevisionService;

class CargaController extends BaseController{
	
	public function index(){
		$User = Auth::user(); //dd(Auth::user());		
		$PostulanteDocumentoService = new PostulanteDocumentoService;	
		$PostulanteModalidadService = new PostulanteModalidadService;
		$Postulante = PostulanteService::postulanteAuth();

		if($Postulante->ESTADO_INSCRIPCION < EstadoInscripcionService::$ESTADO_CONFIRMAR_REGISTRO ){
			return view('Module.InscripcionPostulante.carga-espera');
		}
		if($Postulante->ESTADO_INSCRIPCION >= EstadoInscripcionService::$ESTADO_CONFIRMAR_CARGA_DOCUMENTOS ){
			return view('Module.InscripcionPostulante.carga-finalizada');
		}

		$Modalidades = $PostulanteModalidadService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE,'ID_PROCESO'=>$Postulante->ID_PROCESO]);
		$ModalidadesIdArray = [];
		foreach($Modalidades as $Modalidad){
			array_push($ModalidadesIdArray,$Modalidad->ID_MODALIDAD);
		}		
		$DocumentoModalidadService = new DocumentoModalidadService;
		//$DocumentoArray = $DocumentoModalidadService->listarDeModalidadArray($ModalidadesIdArray);
		$DocumentoLista = $DocumentoModalidadService->listarDeModalidad($ModalidadesIdArray); //dd($DocumentoLista);
		$Documentos = $PostulanteDocumentoService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		$DocumentosPorTipoLista = [];
		foreach($Documentos as $Documento){
			$DocumentosPorTipoLista[$Documento->ID_TIPO_DOCUMENTO][] = $Documento;
		}
		$DocumentoRevisionService = new DocumentoRevisionService;
		$DocumentoRevisionLista = $DocumentoRevisionService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		$DocumentoRevisionLista2 = [];
		foreach($DocumentoRevisionLista as $DocumentoRevision){
			$DocumentoRevisionLista2[$DocumentoRevision->ID_TIPO_DOCUMENTO] = $DocumentoRevision;
		}

		return view('Module.InscripcionPostulante.carga')
			 ->with('DocumentoLista',$DocumentoLista)
			 ->with('Postulante',$Postulante)
			 ->with('DocumentosPorTipoLista',$DocumentosPorTipoLista)
			 ->with('DocumentoRevisionLista',$DocumentoRevisionLista2)		 
			 ;
	}
	public function cargar(){
		$input = Input::all();
		$this->response['input'] = Input::all();
		$new = Input::get('new');

		$rules = ['file' => 'image|max:3000'];
		$validation = Validator::make($input, $rules);
		$PostulanteModalidadService = new PostulanteModalidadService;
		$DocumentoModalidadService = new DocumentoModalidadService;
		$PostulanteDocumentoService = new PostulanteDocumentoService;
		$DocumentoService = new DocumentoService;

		$Postulante = PostulanteService::postulanteAuth();
		
		if ($validation->fails()){
			//return Response::make($validation->errors->first(), 400);
		}else{
			$old['ID_POSTULANTE'] = $Postulante->ID_POSTULANTE;

			$TotalTipo = $PostulanteDocumentoService->totalPorTipoPostulante( $new['ID_TIPO_DOCUMENTO'] , $new['ID_POSTULANTE'] );
			$Documento = $DocumentoService->existe(['DocumentoID'=>$new['ID_TIPO_DOCUMENTO']]);
			if(!$Documento){
				$this->response['error'] = true;
				$this->setResponseTitle('ERROR: TIPO DOCUMENTO NO ENCONTRADO');
	        	return $this->toJSON();
			}
			
			//$this->response['hojas'] = $Documento->Hojas;
			//$this->response['total'] = $TotalTipo;
			$Documento->Hojas = (int) $Documento->Hojas;

			if($Documento->Hojas >0 && $TotalTipo >= $Documento->Hojas){
				$this->response['error'] = true;				
				$this->setResponseTitle('ERROR AL SUBIR DOCUMENTO');
				$this->setResponseMessage('Ya se han subido el número de hojas necesarias.');
	        	return $this->toJSON();	
			}

			$new2['ESTADO_INSCRIPCION'] = EstadoInscripcionService::$ESTADO_CARGA_DOCUMENTOS;
			$new2['FH_ACTUALIZA'] = $this->datetime();
			$PostulanteService = new PostulanteService;
			$rs = $PostulanteService->editar($old,$new2);

			$File = Input::file('file');

			$new['NOMBRE'] = $File->getClientOriginalName();
	        $new['EXTENSION'] = $File->getClientOriginalExtension();

			$MAX_ORDEN = $PostulanteDocumentoService->maxOrden( $new['ID_POSTULANTE'] , $new['ID_TIPO_DOCUMENTO'] );
			$ORDEN = $MAX_ORDEN + 1;
			$Raiz = env('DIRECTORIO_DOCUMENTOS','');			
			$FileName = ($Postulante->ID_PROCESO) .'-'. ($new['ID_TIPO_DOCUMENTO']) .'-'. ($ORDEN) .'-'. ($new['ID_POSTULANTE']) .'.'. $new['EXTENSION'];			
			$Ruta =  $Raiz . $FileName;
			$new['RUTA'] = $Ruta;
			$new['PESO'] = $File->getClientSize();
			$new['ORDEN'] = $ORDEN;
			$new['FH_CREA'] = $this->datetime();			

			$PostulanteDocumentoService->agregar($new);	       
	        	       
	        $upload_success = $File->move($Raiz, $FileName);

	        if( $upload_success ) {
	        	//return Response::json('success', 200);
	        	$this->response['error'] = false;
	        } else {
	        	$this->response['error'] = true;
	        	//return Response::json('error', 400);
	        }
    	}

    	$Modalidades = $PostulanteModalidadService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE,'ID_PROCESO'=>$Postulante->ID_PROCESO]);
		$ModalidadesIdArray = [];
		foreach($Modalidades as $Modalidad){
			array_push($ModalidadesIdArray,$Modalidad->ID_MODALIDAD);
		}
    	//$DocumentoArray = $DocumentoModalidadService->listarDeModalidadArray($ModalidadesIdArray);
    	$TipoDocumentos = $DocumentoModalidadService->listarDeModalidad($ModalidadesIdArray);
    	$Documentos = $PostulanteDocumentoService->listar(['ID_POSTULANTE'=>$new['ID_POSTULANTE']]);
    	//$this->response['TipoDocumentos'] = $DocumentoArray;
    	$this->response['TipoDocumentos'] = $TipoDocumentos;
    	$this->response['Documentos'] = $Documentos;

    	$DocumentoRevisionService = new DocumentoRevisionService;
		$DocumentoRevisionLista = $DocumentoRevisionService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		$DocumentoRevisionLista2 = [];
		foreach($DocumentoRevisionLista as $DocumentoRevision){
			$DocumentoRevisionLista2[$DocumentoRevision->ID_TIPO_DOCUMENTO] = $DocumentoRevision;
		}

		$this->response['DocumentoRevisionLista'] = $DocumentoRevisionLista2;
		
        $this->toJSON();
	}
	function borrar(){
		$ID_POSTULANTE = Input::get('ID_POSTULANTE');
		$ID_POSTULANTE_DOCUMENTO = Input::get('ID_POSTULANTE_DOCUMENTO');
		$this->response['input'] = Input::all();

		$Postulante = PostulanteService::postulanteAuth();
		$old = ['ID_POSTULANTE' => $Postulante->ID_POSTULANTE];
		$new = [
				'ESTADO_INSCRIPCION' => EstadoInscripcionService::$ESTADO_CARGA_DOCUMENTOS 
				,'FH_ACTUALIZA' => $this->datetime()
				];
		$PostulanteService = new PostulanteService;
		$PostulanteService->editar($old,$new);

		$PostulanteDocumentoService = new PostulanteDocumentoService;
		//$Documentos = $PostulanteDocumentoService->listar(['ID_POSTULANTE_DOCUMENTO'=>$ID_POSTULANTE_DOCUMENTO,'ID_POSTULANTE'=>$ID_POSTULANTE]);
		$Documento = $PostulanteDocumentoService->existe(['ID_POSTULANTE_DOCUMENTO'=>$ID_POSTULANTE_DOCUMENTO]);
		if( File::delete($Documento->RUTA) ){
			$rs = $PostulanteDocumentoService->borrarFisico(['ID_POSTULANTE_DOCUMENTO'=>$ID_POSTULANTE_DOCUMENTO]);
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
		$new = Input::has('new')?Input::get('new'):[];
		$old = Input::get('old');
		//$old['FH_CREA'] = $this->datetime();
		$new['FH_DOCUMENTO'] = $this->datetime();
		$new['ESTADO_INSCRIPCION'] = EstadoInscripcionService::$ESTADO_CONFIRMAR_CARGA_DOCUMENTOS;
		$new['FH_ACTUALIZA'] = $this->datetime();
		$PostulanteService = new PostulanteService;
		$rs = $PostulanteService->editar($old,$new);
		return redirect()->to('/inicio');
	}
	public function cancelar(){
		$Postulante = PostulanteService::postulanteAuth();
		$PostulanteDocumentoService = new PostulanteDocumentoService;
		$Documentos = $PostulanteDocumentoService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		if( count($Documentos) == 0){
			$old = ['ID_POSTULANTE' => $Postulante->ID_POSTULANTE];
			$new = [
					'ESTADO_INSCRIPCION' => EstadoInscripcionService::$ESTADO_CONFIRMAR_REGISTRO 
					,'FH_ACTUALIZA' => $this->datetime()
					];
			$PostulanteService = new PostulanteService;
			$rs = $PostulanteService->editar($old,$new);
			return redirect()->to('inicio');
		}else{
			$msg_cancelar = 'Debe borrar los archivos subidos antes de cancelar';
			return redirect()->to('inicio')->with('cancelar',$msg_cancelar);
		}
	}
	public function desconfirmar(){
		$Postulante = PostulanteService::postulanteAuth();
		$old = ['ID_POSTULANTE' => $Postulante->ID_POSTULANTE];
		$new = [
				'ESTADO_INSCRIPCION' => EstadoInscripcionService::$ESTADO_CARGA_DOCUMENTOS 
				,'FH_ACTUALIZA' => $this->datetime()
				];
		$PostulanteService = new PostulanteService;
		$rs = $PostulanteService->editar($old,$new);
		return redirect()->to('inicio');
	}
}