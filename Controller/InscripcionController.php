<?php
namespace App\Module\InscripcionPostulante\Controller;

use DxsRavel\Essentials\Controllers\BaseController;
//use App\Http\Controllers\BaseController;
use Input;
use Auth;
use DateTime;

use App\Module\AccesoPostulante\Service\PostulanteService;
use App\Module\RegistroPostulante\Service\PostulanteRegistroService;

use App\Module\InscripcionPostulante\Service\TipoService;
use App\Module\InscripcionPostulante\Service\UbigeoService;
use App\Module\InscripcionPostulante\Service\PostulanteNacimientoService;
use App\Module\InscripcionPostulante\Service\PostulanteDomicilioService;
use App\Module\InscripcionPostulante\Service\PostulanteResidenciaService;
use App\Module\InscripcionPostulante\Service\PostulanteApoderadoService;
use App\Module\InscripcionPostulante\Service\PostulanteModalidadService;
use App\Module\InscripcionPostulante\Service\ModalidadService;
use App\Module\InscripcionPostulante\Service\VacanteFacultadService;
use App\Module\InscripcionPostulante\Service\VacanteEspecialidadService;
use App\Module\InscripcionPostulante\Service\PostulanteInstruccionService;
use App\Module\InscripcionPostulante\Service\InstitucionEducativaService;
use App\Module\InscripcionPostulante\Service\PostulanteEncuestaService;
use App\Module\InscripcionPostulante\Service\EstadoInscripcionService;

class InscripcionController extends BaseController{
	protected $ID_PAIS_DEFECTO = 1;
	protected $ID_DEPARTAMENTO_DEFECTO = 14;
	protected $ID_PROVINCIA_DEFECTO = 1;
	protected $ID_DISTRITO_DEFECTO = 1;
	protected $ID_PROCESO_DEFECTO = 16;
	protected $ID_MODALIDAD_DEFECTO = 16;		

	public function index(){		
		$User = Auth::user(); //dd(Auth::user());			
		$Postulante = PostulanteService::postulanteAuth();

		if($Postulante->ESTADO_INSCRIPCION >= EstadoInscripcionService::$ESTADO_CONFIRMAR_REGISTRO ){
			return view('Module.InscripcionPostulante.inscripcion-finalizada');
		}

		$TipoService = new TipoService;
		$UbigeoService = new UbigeoService;
		$PostulanteNacimientoService = new PostulanteNacimientoService;
		$PostulanteDomicilioService = new PostulanteDomicilioService;
		$PostulanteRegistroService = new PostulanteRegistroService;
		$PostulanteResidenciaService = new PostulanteResidenciaService;
		$PostulanteApoderadoService = new PostulanteApoderadoService;
		$PostulanteModalidadService = new PostulanteModalidadService;
		$ModalidadService = new ModalidadService;
		$VacanteFacultadService = new VacanteFacultadService;
		$VacanteEspecialidadService = new VacanteEspecialidadService;
		$PostulanteInstruccionService = new PostulanteInstruccionService;
		$InstitucionEducativaService = new InstitucionEducativaService;
		$PostulanteEncuestaService = new PostulanteEncuestaService;

		$SexoArray = $TipoService->listarTablaArray('SEXO','== SELECCIONE ==');

		// DATOS NACIMIENTO //
		$PostulanteNacimiento = $PostulanteNacimientoService->existe(['ID_POSTULANTE' => $User->id ]);
		$PaisesArray = $UbigeoService->PaisArray();
		$PaisId = $PostulanteNacimiento?$PostulanteNacimiento->ID_PAIS:$this->ID_PAIS_DEFECTO;
		$DepartamentosArray = $UbigeoService->DepartamentoArray($PaisId);
		$DepartamentoId = $PostulanteNacimiento?$PostulanteNacimiento->ID_DEPARTAMENTO:$this->ID_DEPARTAMENTO_DEFECTO;
		$ProvinciasArray = $UbigeoService->ProvinciaArray($PaisId,$DepartamentoId);
		$ProvinciaId = $PostulanteNacimiento?$PostulanteNacimiento->ID_PROVINCIA:$this->ID_PROVINCIA_DEFECTO;
		$DistritosArray = $UbigeoService->DistritoArray($PaisId,$DepartamentoId,$ProvinciaId);
		$DistritoId = $PostulanteNacimiento?$PostulanteNacimiento->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Ubigeo = $PostulanteNacimiento?$PostulanteNacimiento->UBIGEO:'';
		$FechaNacimiento = $PostulanteNacimiento?$PostulanteNacimiento->F_NACIMIENTO:'';
		if($PostulanteNacimiento){
			$Date = \DateTime::createFromFormat('Y-m-d',$PostulanteNacimiento->F_NACIMIENTO);
			$FechaNacimiento = $Date->format('d/m/Y');
		}
		// DATOS NACIMIENTO //
		
		// DATOS DOMICILIO //
		$PostulanteDomicilio = $PostulanteDomicilioService->existe(['ID_POSTULANTE' => $User->id ]);
		$PaisesArray3 = $UbigeoService->PaisArray();
		$PaisId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_PAIS:$this->ID_PAIS_DEFECTO;
		$DepartamentosArray3 = $UbigeoService->DepartamentoArray($PaisId3);
		$DepartamentoId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_DEPARTAMENTO:$this->ID_DEPARTAMENTO_DEFECTO;
		$ProvinciasArray3 = $UbigeoService->ProvinciaArray($PaisId3,$DepartamentoId3);
		$ProvinciaId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_PROVINCIA:$this->ID_PROVINCIA_DEFECTO;
		$DistritosArray3 = $UbigeoService->DistritoArray($PaisId3,$DepartamentoId3,$ProvinciaId3);
		$DistritoId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Ubigeo3 = $PostulanteDomicilio?$PostulanteDomicilio->UBIGEO:'';
		$Direccion3 = $PostulanteDomicilio?$PostulanteDomicilio->DIRECCION:'';
		$Telefono3 = $PostulanteDomicilio?$PostulanteDomicilio->TELEFONO:'';
		$Referencia3 = $PostulanteDomicilio?$PostulanteDomicilio->REFERENCIA:'';
		// DATOS DOMICILIO //

		// DATOS RESIDENCIA //
		$PostulanteResidencia = $PostulanteResidenciaService->existe(['ID_POSTULANTE' => $User->id ]);
		$DistritosArray4 = $UbigeoService->DistritoArray($this->ID_PAIS_DEFECTO,$this->ID_DEPARTAMENTO_DEFECTO,$this->ID_PROVINCIA_DEFECTO);
		$DistritoId4 = $PostulanteResidencia?$PostulanteResidencia->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Direccion4 = $PostulanteResidencia?$PostulanteResidencia->DIRECCION:'';
		$Telefono4 = $PostulanteResidencia?$PostulanteResidencia->TELEFONO:'';
		$Referencia4 = $PostulanteResidencia?$PostulanteResidencia->REFERENCIA:'';
		// DATOS RESIDENCIA //

		// DATOS APODERADO //
		$PostulanteApoderado = $PostulanteApoderadoService->existe(['ID_POSTULANTE' => $User->id ]);
		$Paterno5 = $PostulanteApoderado?$PostulanteApoderado->PATERNO:'';
		$Materno5 = $PostulanteApoderado?$PostulanteApoderado->MATERNO:'';
		$Nombres5 = $PostulanteApoderado?$PostulanteApoderado->NOMBRES:'';
		$Dni5 = $PostulanteApoderado?$PostulanteApoderado->DNI:'';
		$Correo5 = $PostulanteApoderado?$PostulanteApoderado->CORREO:'';
		$Telefono5 = $PostulanteApoderado?$PostulanteApoderado->TELEFONO:'';
		$ParentescoId5 = $PostulanteApoderado?$PostulanteApoderado->ID_PARENTESCO:'';
		$ParentescoArray5 = $TipoService->listarTablaArray('PARENTESCO');
		// DATOS APODERADO //
		
		$ProcesoId = $Postulante->ID_PROCESO;
		// DATOS MODALIDAD 1 //
		$PostulanteModalidad1 = $PostulanteModalidadService->existe(['ID_POSTULANTE' => $User->id,'PRIORIDAD'=> 1]);		
		$ModalidadId1 = $PostulanteModalidad1?$PostulanteModalidad1->ID_MODALIDAD:$this->ID_MODALIDAD_DEFECTO;		
		$ModalidadLista1 = $ModalidadService->listar(['Activo'=>1]);		
		$FacultadLista1 = $VacanteFacultadService->listarConVacantes($ProcesoId,$ModalidadId1);
		$Facultad1 = ( $FacultadLista1 && count($FacultadLista1)>0)?$FacultadLista1[0]:false;
		$FacultadId1 = $PostulanteModalidad1?$PostulanteModalidad1->ID_FACULTAD:($Facultad1?$Facultad1->FacultadID:0);
		$EspecialidadLista1 = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId1,$FacultadId1);
		$EspecialidadId1a = $PostulanteModalidad1?$PostulanteModalidad1->ID_ESPECIALIDAD_1:0;
		$EspecialidadId1b = $PostulanteModalidad1?$PostulanteModalidad1->ID_ESPECIALIDAD_2:0;
		// DATOS MODALIDAD 1 //

		// DATOS MODALIDAD 2 //
		$PostulanteModalidad2 = $PostulanteModalidadService->existe(['ID_POSTULANTE' => $User->id,'PRIORIDAD'=> 2]);
		$ModalidadId2 = $PostulanteModalidad2?$PostulanteModalidad2->ID_MODALIDAD:$this->ID_MODALIDAD_DEFECTO;
		$ModalidadLista2 = $ModalidadService->listar(['Activo'=>1]);
		$FacultadLista2 = $VacanteFacultadService->listarConVacantes($ProcesoId,$ModalidadId2);
		$Facultad2 = ( $FacultadLista2 && count($FacultadLista2)>0)?$FacultadLista2[0]:false;
		$FacultadId2 = $PostulanteModalidad2?$PostulanteModalidad2->ID_FACULTAD:($Facultad2?$Facultad2->FacultadID:0);
		$EspecialidadLista2 = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId2,$FacultadId2);
		$EspecialidadId2a = $PostulanteModalidad2?$PostulanteModalidad2->ID_ESPECIALIDAD_1:0;
		$EspecialidadId2b = $PostulanteModalidad2?$PostulanteModalidad2->ID_ESPECIALIDAD_2:0;		
		// DATOS MODALIDAD 2 //

		// DATOS INSTRUCCION //		
		$PostulanteInstruccion = $PostulanteInstruccionService->existe(['ID_POSTULANTE' => $User->id ]);
		$PaisesArray2 = $UbigeoService->PaisArray();
		//$PaisId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_PAIS:$this->ID_PAIS_DEFECTO;
		$PaisId2 = 1;
		$Modalidad1 = $PostulanteModalidad1?($ModalidadService->existe(['ModalidadID'=>$PostulanteModalidad1->ID_MODALIDAD])):false;
		$DepartamentosArray2 = $UbigeoService->DepartamentoArray($PaisId2);
		$DepartamentoId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_DEPARTAMENTO:$this->ID_DEPARTAMENTO_DEFECTO;
		$ProvinciasArray2 = $UbigeoService->ProvinciaArray($PaisId2,$DepartamentoId2);
		$ProvinciaId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_PROVINCIA:$this->ID_PROVINCIA_DEFECTO;
		$DistritosArray2 = $UbigeoService->DistritoArray($PaisId2,$DepartamentoId2,$ProvinciaId2);
		$DistritoId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Ubigeo2 = $PostulanteInstruccion?$PostulanteInstruccion->UBIGEO:'';
		$Tipo = $Modalidad1?$Modalidad1->Tipo:'COLEGIO';
		$InstitucionArray2 = $InstitucionEducativaService->listarArray(['UbigeoID'=>$Ubigeo2,'Tipo'=>$Tipo]);
		$InstitucionId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_INSTITUCION:0;
		// DATOS INSTRUCCION //

		// DATOS ENCUESTA //
		$PostulanteEncuesta = $PostulanteEncuestaService->existe(['ID_POSTULANTE'=>$User->id]);
		$RazonArray = $TipoService->listarTablaArray('RAZON');
		$RazonId = $PostulanteEncuesta?$PostulanteEncuesta->ID_RAZON:0;
		$PreparacionArray = $TipoService->listarTablaArray('PREPARACION');		
		$PreparacionId = $PostulanteEncuesta?$PostulanteEncuesta->ID_PREPARACION:0;
		$PreparacionAñosArray = $TipoService->listarTablaArray('AÑOS DE PREPARACION');
		$PreparacionAñosId = $PostulanteEncuesta?$PostulanteEncuesta->ID_ANOS:0;
		$PreparacionMesesArray = $TipoService->listarTablaArray('MESES DE PREPARACION');
		$PreparacionMesesId = $PostulanteEncuesta?$PostulanteEncuesta->ID_MESES:0;
		$AcademiaArray = $TipoService->listarTablaArray('ACADEMIA');
		$AcademiaId = $PostulanteEncuesta?$PostulanteEncuesta->ID_ACADEMIA:0;
		$PreparacionVecesArray = $TipoService->listarTablaArray('NUMERO DE VECES');
		$PreparacionVecesId = $PostulanteEncuesta?$PostulanteEncuesta->ID_VECES:0;
		$PreparacionRenunciaId = $PostulanteEncuesta?$PostulanteEncuesta->ID_RENUNCIA:0;
		$IngresoFamiliarArray = $TipoService->listarTablaArray('INGRESO FAMILIAR');
		$IngresoFamiliarId = $PostulanteEncuesta?$PostulanteEncuesta->ID_INGRESO_FAMILIAR:0;
		$ContactoArray = $TipoService->listarTablaArray('CONTACTO');
		$ContactoId = $PostulanteEncuesta?$PostulanteEncuesta->ID_MEDIO:0;
		// DATOS ENCUESTA //

		return view('Module.InscripcionPostulante.inscripcion')
			 ->with('Postulante',$Postulante)

			 ->with('SexoArray',$SexoArray)

			 // DATOS NACIMIENTO //
			 ->with('PaisesArray',$PaisesArray)->with('PaisId',$PaisId)
			 ->with('DepartamentosArray',$DepartamentosArray)->with('DepartamentoId',$DepartamentoId)
			 ->with('ProvinciasArray',$ProvinciasArray)->with('ProvinciaId',$ProvinciaId)
			 ->with('DistritosArray',$DistritosArray)->with('DistritoId',$DistritoId)
			 ->with('Ubigeo',$Ubigeo)->with('FechaNacimiento',$FechaNacimiento)
			 // DATOS NACIMIENTO //

			 // DATOS DOMICILIO //
			 ->with('PaisesArray3',$PaisesArray3)->with('PaisId3',$PaisId3)
			 ->with('DepartamentosArray3',$DepartamentosArray3)->with('DepartamentoId3',$DepartamentoId3)
			 ->with('ProvinciasArray3',$ProvinciasArray3)->with('ProvinciaId3',$ProvinciaId3)
			 ->with('DistritosArray3',$DistritosArray3)->with('DistritoId3',$DistritoId3)
			 ->with('Ubigeo3',$Ubigeo3)
			 ->with('Direccion3',$Direccion3)
			 ->with('Telefono3',$Telefono3)
			 ->with('Referencia3',$Referencia3)
			 // DATOS DOMICILIO //

			 // DATOS RESIDENCIA //
			 ->with('DistritosArray4',$DistritosArray4)->with('DistritoId4',$DistritoId4)			
			 ->with('Direccion4',$Direccion4)
			 ->with('Telefono4',$Telefono4)
			 ->with('Referencia4',$Referencia4)
			 // DATOS RESIDENCIA //

			 // DATOS APODERADO //
			 ->with('Paterno5',$Paterno5)
			 ->with('Materno5',$Materno5)
			 ->with('Nombres5',$Nombres5)
			 ->with('Dni5',$Dni5)
			 ->with('Correo5',$Correo5)
			 ->with('Telefono5',$Telefono5)
			 ->with('ParentescoArray5',$ParentescoArray5)->with('ParentescoId5',$ParentescoId5)
			 // DATOS APODERADO //

			 ->with('ProcesoId',$ProcesoId)
			 // DATOS MODALIDAD 1 //
			 ->with('ModalidadLista1',$ModalidadLista1)->with('ModalidadId1',$ModalidadId1)
			 ->with('FacultadLista1',$FacultadLista1)->with('FacultadId1',$FacultadId1)
			 ->with('EspecialidadLista1',$EspecialidadLista1)->with('EspecialidadId1a',$EspecialidadId1a)->with('EspecialidadId1b',$EspecialidadId1b)
			 // DATOS MODALIDAD 1 //
			 
			 // DATOS MODALIDAD 2 //
			 ->with('ModalidadLista2',$ModalidadLista2)->with('ModalidadId2',$ModalidadId2)
			 ->with('FacultadLista2',$FacultadLista2)->with('FacultadId2',$FacultadId2)
			 ->with('EspecialidadLista2',$EspecialidadLista2)->with('EspecialidadId2a',$EspecialidadId2a)->with('EspecialidadId2b',$EspecialidadId2b)
			 // DATOS MODALIDAD 2 //

			 // DATOS INSTRUCCION //
			 ->with('PaisesArray2',$PaisesArray2)->with('PaisId2',$PaisId2)
			 ->with('DepartamentosArray2',$DepartamentosArray2)->with('DepartamentoId2',$DepartamentoId2)
			 ->with('ProvinciasArray2',$ProvinciasArray2)->with('ProvinciaId2',$ProvinciaId2)
			 ->with('DistritosArray2',$DistritosArray2)->with('DistritoId2',$DistritoId2)
			 ->with('Ubigeo2',$Ubigeo2)
			 ->with('InstitucionArray2',$InstitucionArray2)
			 ->with('InstitucionId2',$InstitucionId2)
			 // DATOS INSTRUCCION //

			 // DATOS ENCUESTA //
			 ->with('RazonArray',$RazonArray)->with('RazonId',$RazonId)
			 ->with('PreparacionArray',$PreparacionArray)->with('PreparacionId',$PreparacionId)
			 ->with('PreparacionAñosArray',$PreparacionAñosArray)->with('PreparacionAñosId',$PreparacionAñosId)
			 ->with('PreparacionMesesArray',$PreparacionMesesArray)->with('PreparacionMesesId',$PreparacionMesesId)
			 ->with('AcademiaArray',$AcademiaArray)->with('AcademiaId',$AcademiaId)
			 ->with('PreparacionVecesArray',$PreparacionVecesArray)->with('PreparacionVecesId',$PreparacionVecesId)->with('PreparacionRenunciaId',$PreparacionRenunciaId)
			 ->with('IngresoFamiliarArray',$IngresoFamiliarArray)->with('IngresoFamiliarId',$IngresoFamiliarId)
			 ->with('ContactoArray',$ContactoArray)->with('ContactoId',$ContactoId)
			 // DATOS ENCUESTA //
			 ;
	}
	public function index2(){		
		$User = Auth::user(); //dd(Auth::user());			
		$Postulante = PostulanteService::postulanteAuth();

		if($Postulante->ESTADO_INSCRIPCION >= EstadoInscripcionService::$ESTADO_CONFIRMAR_REGISTRO ){
			return view('Module.InscripcionPostulante.inscripcion-finalizada');
		}

		$TipoService = new TipoService;
		$UbigeoService = new UbigeoService;
		$PostulanteNacimientoService = new PostulanteNacimientoService;
		$PostulanteDomicilioService = new PostulanteDomicilioService;
		$PostulanteRegistroService = new PostulanteRegistroService;
		$PostulanteResidenciaService = new PostulanteResidenciaService;
		$PostulanteApoderadoService = new PostulanteApoderadoService;
		$PostulanteModalidadService = new PostulanteModalidadService;
		$ModalidadService = new ModalidadService;
		$VacanteFacultadService = new VacanteFacultadService;
		$VacanteEspecialidadService = new VacanteEspecialidadService;
		$PostulanteInstruccionService = new PostulanteInstruccionService;
		$InstitucionEducativaService = new InstitucionEducativaService;
		$PostulanteEncuestaService = new PostulanteEncuestaService;

		$SexoArray = $TipoService->listarTablaArray('SEXO','== SELECCIONE ==');

		// DATOS NACIMIENTO //
		$PostulanteNacimiento = $PostulanteNacimientoService->existe(['ID_POSTULANTE' => $User->id ]);
		$PaisesArray = $UbigeoService->PaisArray();
		$PaisId = $PostulanteNacimiento?$PostulanteNacimiento->ID_PAIS:$this->ID_PAIS_DEFECTO;
		$DepartamentosArray = $UbigeoService->DepartamentoArray($PaisId);
		$DepartamentoId = $PostulanteNacimiento?$PostulanteNacimiento->ID_DEPARTAMENTO:$this->ID_DEPARTAMENTO_DEFECTO;
		$ProvinciasArray = $UbigeoService->ProvinciaArray($PaisId,$DepartamentoId);
		$ProvinciaId = $PostulanteNacimiento?$PostulanteNacimiento->ID_PROVINCIA:$this->ID_PROVINCIA_DEFECTO;
		$DistritosArray = $UbigeoService->DistritoArray($PaisId,$DepartamentoId,$ProvinciaId);
		$DistritoId = $PostulanteNacimiento?$PostulanteNacimiento->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Distrito = isset($DistritosArray[$DistritoId])?$DistritosArray[$DistritoId]:false;
		$Ubigeo = $PostulanteNacimiento?$PostulanteNacimiento->UBIGEO:($Distrito?$Distrito->UbigeoID:'');
		$FechaNacimiento = $PostulanteNacimiento?$PostulanteNacimiento->F_NACIMIENTO:'';
		if($PostulanteNacimiento){
			$Date = \DateTime::createFromFormat('Y-m-d',$PostulanteNacimiento->F_NACIMIENTO);
			$FechaNacimiento = $Date->format('d/m/Y');
		}
		// DATOS NACIMIENTO //
		
		// DATOS DOMICILIO //
		$PostulanteDomicilio = $PostulanteDomicilioService->existe(['ID_POSTULANTE' => $User->id ]);
		$PaisesArray3 = $UbigeoService->PaisArray();
		$PaisId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_PAIS:$this->ID_PAIS_DEFECTO;
		$DepartamentosArray3 = $UbigeoService->DepartamentoArray($PaisId3);
		$DepartamentoId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_DEPARTAMENTO:$this->ID_DEPARTAMENTO_DEFECTO;
		$ProvinciasArray3 = $UbigeoService->ProvinciaArray($PaisId3,$DepartamentoId3);
		$ProvinciaId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_PROVINCIA:$this->ID_PROVINCIA_DEFECTO;
		$DistritosArray3 = $UbigeoService->DistritoArray($PaisId3,$DepartamentoId3,$ProvinciaId3);
		$DistritoId3 = $PostulanteDomicilio?$PostulanteDomicilio->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Distrito3 = isset($DistritosArray3[$DistritoId3])?$DistritosArray3[$DistritoId3]:false;
		$Ubigeo3 = $PostulanteDomicilio?$PostulanteDomicilio->UBIGEO:($Distrito3?$Distrito3->UbigeoID:'');
		$Direccion3 = $PostulanteDomicilio?$PostulanteDomicilio->DIRECCION:'';
		$Telefono3 = $PostulanteDomicilio?$PostulanteDomicilio->TELEFONO:'';
		$Referencia3 = $PostulanteDomicilio?$PostulanteDomicilio->REFERENCIA:'';
		// DATOS DOMICILIO //

		// DATOS RESIDENCIA //
		$PostulanteResidencia = $PostulanteResidenciaService->existe(['ID_POSTULANTE' => $User->id ]);
		$DistritosArray4 = $UbigeoService->DistritoArray($this->ID_PAIS_DEFECTO,$this->ID_DEPARTAMENTO_DEFECTO,$this->ID_PROVINCIA_DEFECTO);
		$DistritoId4 = $PostulanteResidencia?$PostulanteResidencia->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Direccion4 = $PostulanteResidencia?$PostulanteResidencia->DIRECCION:'';
		$Telefono4 = $PostulanteResidencia?$PostulanteResidencia->TELEFONO:'';
		$Referencia4 = $PostulanteResidencia?$PostulanteResidencia->REFERENCIA:'';
		// DATOS RESIDENCIA //

		// DATOS APODERADO //
		$PostulanteApoderado = $PostulanteApoderadoService->existe(['ID_POSTULANTE' => $User->id ]);
		$Paterno5 = $PostulanteApoderado?$PostulanteApoderado->PATERNO:'';
		$Materno5 = $PostulanteApoderado?$PostulanteApoderado->MATERNO:'';
		$Nombres5 = $PostulanteApoderado?$PostulanteApoderado->NOMBRES:'';
		$Dni5 = $PostulanteApoderado?$PostulanteApoderado->DNI:'';
		$Correo5 = $PostulanteApoderado?$PostulanteApoderado->CORREO:'';
		$Telefono5 = $PostulanteApoderado?$PostulanteApoderado->TELEFONO:'';
		$ParentescoId5 = $PostulanteApoderado?$PostulanteApoderado->ID_PARENTESCO:'';
		$ParentescoArray5 = $TipoService->listarTablaArray('PARENTESCO');
		// DATOS APODERADO //
		
		$ProcesoId = $Postulante->ID_PROCESO;
		// DATOS MODALIDAD 1 //
		$PostulanteModalidad1 = $PostulanteModalidadService->existe(['ID_POSTULANTE' => $User->id,'PRIORIDAD'=> 1]);		
		$FacultadLista1 = $VacanteFacultadService->listarConVacantes($ProcesoId);
		$Facultad1 = ( $FacultadLista1 && count($FacultadLista1)>0)?$FacultadLista1[0]:false;
		$FacultadId1 = $PostulanteModalidad1?$PostulanteModalidad1->ID_FACULTAD:($Facultad1?$Facultad1->FacultadID:0);

		//$ModalidadLista1 = $ModalidadService->listar(['Activo'=>1]);		
		$ModalidadLista1 = $VacanteFacultadService->listarModalidadesConVacantes($ProcesoId,$FacultadId1);
		$Modalidad1 = ( $ModalidadLista1 && count($ModalidadLista1)>0)?$ModalidadLista1[0]:false;
		$ModalidadId1 = $PostulanteModalidad1?$PostulanteModalidad1->ID_MODALIDAD:($Modalidad1?$Modalidad1->ModalidadID:$this->ID_MODALIDAD_DEFECTO);
		
		$EspecialidadLista1 = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId1,$FacultadId1);
		$EspecialidadId1a = $PostulanteModalidad1?$PostulanteModalidad1->ID_ESPECIALIDAD_1:0;
		$EspecialidadId1b = $PostulanteModalidad1?$PostulanteModalidad1->ID_ESPECIALIDAD_2:0;
		// DATOS MODALIDAD 1 //

		// DATOS MODALIDAD 2 //
		$PostulanteModalidad2 = $PostulanteModalidadService->existe(['ID_POSTULANTE' => $User->id,'PRIORIDAD'=> 2]);		
		$FacultadLista2 = $VacanteFacultadService->listarConVacantes($ProcesoId);
		$Facultad2 = ( $FacultadLista2 && count($FacultadLista2)>0)?$FacultadLista2[0]:false;
		$FacultadId2 = $PostulanteModalidad2?$PostulanteModalidad2->ID_FACULTAD:($Facultad2?$Facultad2->FacultadID:0);

		//$ModalidadLista2 = $ModalidadService->listar(['Activo'=>1]);		
		$ModalidadLista2 = $VacanteFacultadService->listarModalidadesConVacantes($ProcesoId,$FacultadId2);
		$Modalidad2 = ( $ModalidadLista2 && count($ModalidadLista2)>0)?$ModalidadLista2[0]:false;
		$ModalidadId2 = $PostulanteModalidad2?$PostulanteModalidad2->ID_MODALIDAD:($Modalidad2?$Modalidad2->ModalidadID:$this->ID_MODALIDAD_DEFECTO);		

		$EspecialidadLista2 = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId2,$FacultadId2);
		$EspecialidadId2a = $PostulanteModalidad2?$PostulanteModalidad2->ID_ESPECIALIDAD_1:0;
		$EspecialidadId2b = $PostulanteModalidad2?$PostulanteModalidad2->ID_ESPECIALIDAD_2:0;		
		// DATOS MODALIDAD 2 //

		// DATOS INSTRUCCION //		
		$PostulanteInstruccion = $PostulanteInstruccionService->existe(['ID_POSTULANTE' => $User->id ]);
		$PaisesArray2 = $UbigeoService->PaisArray();
		$PaisId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_PAIS:$this->ID_PAIS_DEFECTO;
		//$PaisId2 = 1;
		$Modalidad1 = $PostulanteModalidad1?($ModalidadService->existe(['ModalidadID'=>$PostulanteModalidad1->ID_MODALIDAD])):false;
		$DepartamentosArray2 = $UbigeoService->DepartamentoArray($PaisId2);
		$DepartamentoId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_DEPARTAMENTO:$this->ID_DEPARTAMENTO_DEFECTO;
		$ProvinciasArray2 = $UbigeoService->ProvinciaArray($PaisId2,$DepartamentoId2);
		$ProvinciaId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_PROVINCIA:$this->ID_PROVINCIA_DEFECTO;
		$DistritosArray2 = $UbigeoService->DistritoArray($PaisId2,$DepartamentoId2,$ProvinciaId2);
		$DistritoId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_DISTRITO:$this->ID_DISTRITO_DEFECTO;
		$Distrito2 = isset($DistritosArray2[$DistritoId2])?$DistritosArray2[$DistritoId2]:false;
		$Ubigeo2 = $PostulanteInstruccion?$PostulanteInstruccion->UBIGEO:($Distrito2?$Distrito2->UbigeoID:'');
		$Tipo = $Modalidad1?$Modalidad1->Tipo:'COLEGIO';
		$InstitucionArray2 = $InstitucionEducativaService->listarArray(['UbigeoID'=>$Ubigeo2,'Tipo'=>$Tipo],false,['NOMBRE'=>'ASC']);
		//dd($InstitucionArray2);

		$InstitucionId2 = $PostulanteInstruccion?$PostulanteInstruccion->ID_INSTITUCION:0;
		// DATOS INSTRUCCION //

		// DATOS ENCUESTA //
		$PostulanteEncuesta = $PostulanteEncuestaService->existe(['ID_POSTULANTE'=>$User->id]);
		$RazonArray = $TipoService->listarTablaArray('RAZON');
		$RazonId = $PostulanteEncuesta?$PostulanteEncuesta->ID_RAZON:0;
		$PreparacionArray = $TipoService->listarTablaArray('PREPARACION');		
		$PreparacionId = $PostulanteEncuesta?$PostulanteEncuesta->ID_PREPARACION:0;
		$PreparacionAñosArray = $TipoService->listarTablaArray('AÑOS DE PREPARACION');
		$PreparacionAñosId = $PostulanteEncuesta?$PostulanteEncuesta->ID_ANOS:0;
		$PreparacionMesesArray = $TipoService->listarTablaArray('MESES DE PREPARACION');
		$PreparacionMesesId = $PostulanteEncuesta?$PostulanteEncuesta->ID_MESES:0;
		$AcademiaArray = $TipoService->listarTablaArray('ACADEMIA');
		$AcademiaId = $PostulanteEncuesta?$PostulanteEncuesta->ID_ACADEMIA:0;
		$PreparacionVecesArray = $TipoService->listarTablaArray('NUMERO DE VECES');
		$PreparacionVecesId = $PostulanteEncuesta?$PostulanteEncuesta->ID_VECES:0;
		$PreparacionRenunciaId = $PostulanteEncuesta?$PostulanteEncuesta->ID_RENUNCIA:0;
		$IngresoFamiliarArray = $TipoService->listarTablaArray('INGRESO FAMILIAR');
		$IngresoFamiliarId = $PostulanteEncuesta?$PostulanteEncuesta->ID_INGRESO_FAMILIAR:0;
		$ContactoArray = $TipoService->listarTablaArray('CONTACTO');
		$ContactoId = $PostulanteEncuesta?$PostulanteEncuesta->ID_MEDIO:0;
		// DATOS ENCUESTA //

		return view('Module.InscripcionPostulante.inscripcion2')
			 ->with('Postulante',$Postulante)

			 ->with('SexoArray',$SexoArray)

			 // DATOS NACIMIENTO //
			 ->with('PaisesArray',$PaisesArray)->with('PaisId',$PaisId)
			 ->with('DepartamentosArray',$DepartamentosArray)->with('DepartamentoId',$DepartamentoId)
			 ->with('ProvinciasArray',$ProvinciasArray)->with('ProvinciaId',$ProvinciaId)
			 ->with('DistritosArray',$DistritosArray)->with('DistritoId',$DistritoId)
			 ->with('Ubigeo',$Ubigeo)->with('FechaNacimiento',$FechaNacimiento)
			 // DATOS NACIMIENTO //

			 // DATOS DOMICILIO //
			 ->with('PaisesArray3',$PaisesArray3)->with('PaisId3',$PaisId3)
			 ->with('DepartamentosArray3',$DepartamentosArray3)->with('DepartamentoId3',$DepartamentoId3)
			 ->with('ProvinciasArray3',$ProvinciasArray3)->with('ProvinciaId3',$ProvinciaId3)
			 ->with('DistritosArray3',$DistritosArray3)->with('DistritoId3',$DistritoId3)
			 ->with('Ubigeo3',$Ubigeo3)
			 ->with('Direccion3',$Direccion3)
			 ->with('Telefono3',$Telefono3)
			 ->with('Referencia3',$Referencia3)
			 // DATOS DOMICILIO //

			 // DATOS RESIDENCIA //
			 ->with('DistritosArray4',$DistritosArray4)->with('DistritoId4',$DistritoId4)			
			 ->with('Direccion4',$Direccion4)
			 ->with('Telefono4',$Telefono4)
			 ->with('Referencia4',$Referencia4)
			 // DATOS RESIDENCIA //

			 // DATOS APODERADO //
			 ->with('Paterno5',$Paterno5)
			 ->with('Materno5',$Materno5)
			 ->with('Nombres5',$Nombres5)
			 ->with('Dni5',$Dni5)
			 ->with('Correo5',$Correo5)
			 ->with('Telefono5',$Telefono5)
			 ->with('ParentescoArray5',$ParentescoArray5)->with('ParentescoId5',$ParentescoId5)
			 // DATOS APODERADO //

			 ->with('ProcesoId',$ProcesoId)
			 // DATOS MODALIDAD 1 //
			 ->with('ModalidadLista1',$ModalidadLista1)->with('ModalidadId1',$ModalidadId1)
			 ->with('FacultadLista1',$FacultadLista1)->with('FacultadId1',$FacultadId1)
			 ->with('EspecialidadLista1',$EspecialidadLista1)->with('EspecialidadId1a',$EspecialidadId1a)->with('EspecialidadId1b',$EspecialidadId1b)
			 // DATOS MODALIDAD 1 //
			 
			 // DATOS MODALIDAD 2 //
			 ->with('ModalidadLista2',$ModalidadLista2)->with('ModalidadId2',$ModalidadId2)
			 ->with('FacultadLista2',$FacultadLista2)->with('FacultadId2',$FacultadId2)
			 ->with('EspecialidadLista2',$EspecialidadLista2)->with('EspecialidadId2a',$EspecialidadId2a)->with('EspecialidadId2b',$EspecialidadId2b)
			 // DATOS MODALIDAD 2 //

			 // DATOS INSTRUCCION //
			 ->with('PaisesArray2',$PaisesArray2)->with('PaisId2',$PaisId2)
			 ->with('DepartamentosArray2',$DepartamentosArray2)->with('DepartamentoId2',$DepartamentoId2)
			 ->with('ProvinciasArray2',$ProvinciasArray2)->with('ProvinciaId2',$ProvinciaId2)
			 ->with('DistritosArray2',$DistritosArray2)->with('DistritoId2',$DistritoId2)
			 ->with('Ubigeo2',$Ubigeo2)
			 ->with('InstitucionArray2',$InstitucionArray2)
			 ->with('InstitucionId2',$InstitucionId2)
			 // DATOS INSTRUCCION //

			 // DATOS ENCUESTA //
			 ->with('RazonArray',$RazonArray)->with('RazonId',$RazonId)
			 ->with('PreparacionArray',$PreparacionArray)->with('PreparacionId',$PreparacionId)
			 ->with('PreparacionAñosArray',$PreparacionAñosArray)->with('PreparacionAñosId',$PreparacionAñosId)
			 ->with('PreparacionMesesArray',$PreparacionMesesArray)->with('PreparacionMesesId',$PreparacionMesesId)
			 ->with('AcademiaArray',$AcademiaArray)->with('AcademiaId',$AcademiaId)
			 ->with('PreparacionVecesArray',$PreparacionVecesArray)->with('PreparacionVecesId',$PreparacionVecesId)->with('PreparacionRenunciaId',$PreparacionRenunciaId)
			 ->with('IngresoFamiliarArray',$IngresoFamiliarArray)->with('IngresoFamiliarId',$IngresoFamiliarId)
			 ->with('ContactoArray',$ContactoArray)->with('ContactoId',$ContactoId)
			 // DATOS ENCUESTA //
			 ;
	}
	public function guardarPostulante(){
		$new = Input::get('new');
		$old = Input::get('old');
		$PostulanteService = new PostulanteService;
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);

		$rs = $PostulanteService->editar($old,$new);
		if(!$rs){
			$this->setResponseTitle('ERROR ACTUALIZANDO');
			$this->setResponseMessage('No se pudo actualizar los datos.');
		}else{
			$this->setResponseTitle('ACTUALIZADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}

		$this->respose['error'] = !$rs;
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarNacimiento(){
		$new = Input::get('new');
		$old = Input::get('old');
		$date = DateTime::createFromFormat('d/m/Y',$new['F_NACIMIENTO']);
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
		if($date){
			$old['FH_CREA'] = $this->datetime();
			$new['FH_ACTUALIZA'] = $this->datetime();
			$new['F_NACIMIENTO'] = $date->format('Y-m-d');			
			$PostulanteNacimientoService = new PostulanteNacimientoService;
			$rs = $PostulanteNacimientoService->editarOcrear($old,$new);
			if(!$rs){
				$this->setResponseTitle('ERROR GUARDANDO DATOS');
				$this->setResponseMessage('No se pudo guardar los datos.');
			}else{
				$this->setResponseTitle('GUARDADO CORRECTAMENTE');
				//$this->setResponseMessage('No se pudo actualizar los datos.');
			}
			$this->response['error'] = !$rs;
		}else{
			$this->response['error'] = true;			
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('Formato de fecha incorrecto.');
		}		
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarDomicilio(){
		$new = Input::get('new');
		$old = Input::get('old');
		$old['FH_CREA'] = $this->datetime();
		$new['FH_ACTUALIZA'] = $this->datetime();
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
						
		$PostulanteDomicilioService = new PostulanteDomicilioService;
		$rs = $PostulanteDomicilioService->editarOcrear($old,$new);
		if(!$rs){
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('No se pudo guardar los datos.');
		}else{
			$this->setResponseTitle('GUARDADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}
		$this->response['error'] = !$rs;
			
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarResidencia(){
		$new = Input::get('new');
		$old = Input::get('old');
		$old['FH_CREA'] = $this->datetime();
		$new['FH_ACTUALIZA'] = $this->datetime();
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
						
		$PostulanteResidenciaService = new PostulanteResidenciaService;
		$rs = $PostulanteResidenciaService->editarOcrear($old,$new);
		if(!$rs){
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('No se pudo guardar los datos.');
		}else{
			$this->setResponseTitle('GUARDADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}
		$this->response['error'] = !$rs;
			
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarApoderado(){
		$new = Input::get('new');
		$old = Input::get('old');
		$old['FH_CREA'] = $this->datetime();
		$new['FH_ACTUALIZA'] = $this->datetime();
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
						
		$PostulanteApoderadoService = new PostulanteApoderadoService;
		$rs = $PostulanteApoderadoService->editarOcrear($old,$new);
		if(!$rs){
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('No se pudo guardar los datos.');
		}else{
			$this->setResponseTitle('GUARDADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}
		$this->response['error'] = !$rs;
			
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarModalidad(){
		$new = Input::get('new');
		$old = Input::get('old');
		$old['FH_CREA'] = $this->datetime();
		$new['FH_ACTUALIZA'] = $this->datetime();
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
						
		$ModalidadService = new ModalidadService;
		$PostulanteModalidadService = new PostulanteModalidadService;

		if($old['PRIORIDAD'] == 1){
			$Modalidad = $ModalidadService->existe(['ModalidadID'=>$new['ID_MODALIDAD']]);
			if($Modalidad && !$Modalidad->Multiple){
				$PostulanteModalidadService->borrarFisico(['ID_POSTULANTE' => $old['ID_POSTULANTE'],'PRIORIDAD'=>2]);
			}
		}
		if($new['ID_MODALIDAD']==0){
			$rs = $PostulanteModalidadService->borrarFisico($old);
		}else{
			$rs = $PostulanteModalidadService->editarOcrear($old,$new);
		}
		if(!$rs){
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('No se pudo guardar los datos.');
		}else{
			$this->setResponseTitle('GUARDADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}
		$this->response['error'] = !$rs;

		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarInstruccion(){
		$new = Input::get('new');
		$old = Input::get('old');
		$old['FH_CREA'] = $this->datetime();
		$new['FH_ACTUALIZA'] = $this->datetime();
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
						
		$PostulanteInstruccionService = new PostulanteInstruccionService;
		$rs = $PostulanteInstruccionService->editarOcrear($old,$new);
		if(!$rs){
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('No se pudo guardar los datos.');
		}else{
			$this->setResponseTitle('GUARDADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}
		$this->response['error'] = !$rs;
			
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function guardarEncuesta(){
		$new = Input::get('new');
		$old = Input::get('old');
		$old['FH_CREA'] = $this->datetime();
		$new['FH_ACTUALIZA'] = $this->datetime();
		PostulanteService::actualizaEstadoInscripcion($old['ID_POSTULANTE'],2);
						
		$PostulanteEncuestaService = new PostulanteEncuestaService;
		$rs = $PostulanteEncuestaService->editarOcrear($old,$new);
		if(!$rs){
			$this->setResponseTitle('ERROR GUARDANDO DATOS');
			$this->setResponseMessage('No se pudo guardar los datos.');
		}else{
			$this->setResponseTitle('GUARDADO CORRECTAMENTE');
			//$this->setResponseMessage('No se pudo actualizar los datos.');
		}
		$this->response['error'] = !$rs;
			
		$this->response['input'] = Input::all();
		$this->toJSON();
	}	
	public function departamentos(){
		$PaisId = Input::has('ID_PAIS')?Input::get('ID_PAIS'):1;		
		$UbigeoService = new UbigeoService;

		$ProvinciaId = 1;
		$DepartamentoId = 1;
		$DepartamentosArray = $UbigeoService->DepartamentoArray($PaisId);
		$ProvinciasArray = $UbigeoService->ProvinciaArray($PaisId,$DepartamentoId);
		$DistritosArray = $UbigeoService->DistritoArray($PaisId,$DepartamentoId,$ProvinciaId); 
		$this->response['Departamentos'] = $DepartamentosArray;
		$this->response['Provincias'] = $ProvinciasArray;
		$this->response['Distritos'] = $DistritosArray;
		$this->toJSON();
	}
	public function provincias(){
		$PaisId = Input::has('ID_PAIS')?Input::get('ID_PAIS'):1;
		$DepartamentoId = Input::has('ID_DEPT')?Input::get('ID_DEPT'):0;
		$UbigeoService = new UbigeoService;
		
		$ProvinciaId = 1;
		$ProvinciasArray = $UbigeoService->ProvinciaArray($PaisId,$DepartamentoId);
		$DistritosArray = $UbigeoService->DistritoArray($PaisId,$DepartamentoId,$ProvinciaId); 
		$this->response['Provincias'] = $ProvinciasArray;
		$this->response['Distritos'] = $DistritosArray;
		$this->toJSON();
	}
	public function distritos(){
		$PaisId = Input::has('ID_PAIS')?Input::get('ID_PAIS'):1;
		$DepartamentoId = Input::has('ID_DEPT')?Input::get('ID_DEPT'):0;
		$ProvinciaId = Input::has('ID_PROV')?Input::get('ID_PROV'):0;
		$UbigeoService = new UbigeoService;
						
		$DistritosArray = $UbigeoService->DistritoArray($PaisId,$DepartamentoId,$ProvinciaId); 		
		$this->response['Distritos'] = $DistritosArray;
		$this->toJSON();
	}
	public function facultades(){
		$ProcesoId = Input::has('ID_PROCESO')?Input::get('ID_PROCESO'):0;
		$ModalidadId = Input::has('ID_MODALIDAD')?Input::get('ID_MODALIDAD'):0;
		$VacanteFacultadService = new VacanteFacultadService;
		$VacanteEspecialidadService = new VacanteEspecialidadService;
		
		$FacultadLista = $VacanteFacultadService->listarConVacantes($ProcesoId,$ModalidadId);		
		$Facultad = ( $FacultadLista && count($FacultadLista)>0)?$FacultadLista[0]:false;
		$FacultadId = $Facultad?$Facultad->FacultadID:0;
		$EspecialidadLista = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId,$FacultadId);

		$this->response['Facultades'] = $FacultadLista;		
		$this->response['Especialidades'] = $EspecialidadLista;
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function modalidades(){
		$ProcesoId = Input::has('ID_PROCESO')?Input::get('ID_PROCESO'):0;
		//$ModalidadId = Input::has('ID_MODALIDAD')?Input::get('ID_MODALIDAD'):0;
		$FacultadId = Input::has('ID_FACULTAD')?Input::get('ID_FACULTAD'):0;
		$VacanteFacultadService = new VacanteFacultadService;
		$VacanteEspecialidadService = new VacanteEspecialidadService;
		
		$ModalidadLista = $VacanteFacultadService->listarModalidadesConVacantes($ProcesoId,$FacultadId);		
		$Modalidad = ( $ModalidadLista && count($ModalidadLista)>0)?$ModalidadLista[0]:false;
		$ModalidadId = $Modalidad?$Modalidad->ModalidadID:0;
		$EspecialidadLista = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId,$FacultadId);

		$this->response['Modalidades'] = $ModalidadLista;		
		$this->response['Especialidades'] = $EspecialidadLista;
		$this->response['input'] = Input::all();
		$this->toJSON();
	}
	public function especialidades(){
		$ProcesoId = Input::has('ID_PROCESO')?Input::get('ID_PROCESO'):0;
		$ModalidadId = Input::has('ID_MODALIDAD')?Input::get('ID_MODALIDAD'):0;
		$FacultadId = Input::has('ID_FACULTAD')?Input::get('ID_FACULTAD'):0;		
		$VacanteEspecialidadService = new VacanteEspecialidadService;
								
		$EspecialidadLista = $VacanteEspecialidadService->listarConVacantes($ProcesoId,$ModalidadId,$FacultadId);
		
		$this->response['Especialidades'] = $EspecialidadLista;
		$this->toJSON();
	}
	public function instituciones(){
		$Tipo = Input::has('TIPO')?Input::get('TIPO'):'COLEGIO';
		$Ubigeo = Input::has('UBIGEO')?Input::get('UBIGEO'):0;
		$InstitucionEducativaService = new InstitucionEducativaService;

		$InstitucionLista = $InstitucionEducativaService->listar(['Tipo'=>$Tipo,'UbigeoID'=>$Ubigeo],['NOMBRE'=>'ASC']);

		$this->response['Instituciones'] = $InstitucionLista;
		$this->toJSON();
	}
	public function finalizar(){
		$new = Input::has('new')?Input::get('new'):[];
		$old = Input::get('old');
		//$old['FH_CREA'] = $this->datetime();
		$new['FH_INSCRIPCION'] = $this->datetime();
		$new['ESTADO_INSCRIPCION'] = EstadoInscripcionService::$ESTADO_CONFIRMAR_REGISTRO;
		$PostulanteService = new PostulanteService;
		$rs = $PostulanteService->editar($old,$new);
		return redirect()->to('/inicio');
	}
	public function cancelar(){
		$Postulante = PostulanteService::postulanteAuth();
		$old = ['ID_POSTULANTE' => $Postulante->ID_POSTULANTE];
		$new = ['ESTADO_INSCRIPCION' => EstadoInscripcionService::$ESTADO_INGRESAR_REGISTRO ];
		$PostulanteService = new PostulanteService;
		$rs = $PostulanteService->editar($old,$new);
		return redirect()->to('inicio');
		$msg_cancelar = 'No se puede cancelar';
		return redirect()->to('inicio')->with('cancelar',$msg_cancelar);
	}
}
