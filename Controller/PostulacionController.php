<?php
namespace App\Module\InscripcionPostulante\Controller;

use DxsRavel\Essentials\Controllers\BaseController;
//use App\Http\Controllers\BaseController;
use Input;
use Auth;
use DateTime;

use App\Module\AccesoUsuario\Service\PostulanteService;
use App\Module\RegistroUsuario\Service\PostulanteRegistroService;

use App\Module\InscripcionPostulante\Service\PostulacionTipoService;

use App\Module\GestorFlujo\Service\GestorFlujoService;
use App\Module\GestorFlujo\Service\FlujoService;
use App\Module\GestorFlujo\Service\ObjetoFlujoService;

class PostulacionController extends BaseController{

	public function index(){
		$User = Auth::user(); //dd(Auth::user());			
		$Postulante = PostulanteService::postulanteAuth();

		$PostulacionTipoService = new PostulacionTipoService;
		$PostulacionTipoLista = $PostulacionTipoService->listarActivos();

		return view('InscripcionPostulante::postulacion')
			 ->with('Postulante',$Postulante)

			 ->with('PostulacionTipoLista',$PostulacionTipoLista)
			 ;
	}
	public function iniciar(){
		$Postulante = PostulanteService::postulanteAuth();

		$GestorFlujoService = new GestorFlujoService;
		$FlujoService = new FlujoService;

		$PostulacionTipoService = new PostulacionTipoService;
		$PostulacionTipo = $PostulacionTipoService->primero(['ID_POSTULACION_TIPO'=>Input::get('ID_POSTULACION_TIPO')]);

		$Flujo = $FlujoService->primero(['ID_FLUJO'=>$PostulacionTipo->ID_FLUJO]); //dd($Flujo);

		$GestorFlujoService->asignarFlujo($Postulante,$Flujo);
		return redirect()->route('gestor-flujo.proceso');
	}
	public function proceso(){
		$Postulante = PostulanteService::postulanteAuth();
		$ObjetoFlujoService = new ObjetoFlujoService;

		$ObjetoFlujo = $ObjetoFlujoService->obtenerPrimerFlujoSinFinalizar($Postulante->ID_POSTULANTE);
		if(!$ObjetoFlujo){
			return redirect()->route('postulacion');
		}
		$ObjetoProcesoLista = $ObjetoFlujoService->ObjetoProcesoLista();

		return view('InscripcionPostulante::proceso')
			 ->with('ObjetoFlujo',$ObjetoFlujo)
			 ->with('ObjetoProcesoLista',$ObjetoProcesoLista)
			 ;
	}
}