<?php
namespace App\Module\InscripcionPostulante\Controller;

use DxsRavel\Essentials\Controllers\BaseController;
use DxsRavel\Essentials\Pdf\BaseFpdf;
use Input;
use Auth;
use DateTime;
use DPDF;
use DB;
use File;

use App\Module\AccesoPostulante\Service\PostulanteService;
use App\Module\RegistroPostulante\Service\PostulanteRegistroService;
use App\Module\InscripcionPostulante\Service\PostulanteModalidadService;
use App\Module\InscripcionPostulante\Service\PostulanteNacimientoService;
use App\Module\InscripcionPostulante\Service\PostulanteDomicilioService;
use App\Module\InscripcionPostulante\Service\PostulanteResidenciaService;
use App\Module\InscripcionPostulante\Service\PostulanteDocumentoService;
use App\Module\InscripcionPostulante\Service\PostulanteInstruccionService;
use App\Module\InscripcionPostulante\Service\UbigeoService;

class ConstanciaController extends BaseController{
	protected $ID_DOCUMENTO_FOTO = 2278;

	public function fichaPostulantePdf(){
		$Postulante = PostulanteService::postulanteAuth();
		$PostulanteModalidadService = new PostulanteModalidadService;
		$Modalidades = $PostulanteModalidadService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		foreach($Modalidades as &$Modalidad){
			$Modalidad->Modalidad = DB::table('Modalidad')->where('ModalidadID',$Modalidad->ID_MODALIDAD)->first();
			$Modalidad->Facultad = DB::table('Facultad')->where('FacultadID',$Modalidad->ID_FACULTAD)->first();
			$Modalidad->Especialidad1 = $Modalidad->ID_ESPECIALIDAD_1?( DB::table('Especialidad')->where('EspecialidadID',$Modalidad->ID_ESPECIALIDAD_1)->first() ):false;
			$Modalidad->Especialidad2 = $Modalidad->ID_ESPECIALIDAD_2?( DB::table('Especialidad')->where('EspecialidadID',$Modalidad->ID_ESPECIALIDAD_2)->first() ):false;
		}
		return DPDF::loadView('postulante.dompdf.ficha-de-postulante',
			['Postulante'=>$Postulante,'Modalidades'=>$Modalidades])
		->setPaper('A4', 'landscape')->stream();
	}
	public function pagoInscripcionPdfSbp(){
		$Postulante = PostulanteService::postulanteAuth();
		$Pagos = DB::select(DB::Raw('select po.ID_POSTULANTE,po.NUM_IDENTIFICACION,po.PATERNO,po.MATERNO,po.NOMBRES,pa.MONTO,pa.ID_MODALIDAD_CONCEPTO_PAGO,cp.DESCRIPCION,cp.CODIGO,cp.SCOTIABANK_LETRA 
									FROM TB_POSTULANTE po 
									INNER JOIN TB_POSTULANTE_PAGO pa on (po.ID_POSTULANTE=pa.ID_POSTULANTE)
									INNER JOIN TB_CONCEPTO_PAGO cp on (cp.ID_CONCEPTO_PAGO=pa.ID_MODALIDAD_CONCEPTO_PAGO)
									where ID_MODALIDAD_CONCEPTO_PAGO!=13 and pa.ID_POSTULANTE='.$Postulante->ID_POSTULANTE));
	//	dd($Pagos);

		$Postulante = PostulanteService::postulanteAuth();
		$Pdf = new BaseFpdf('L','mm','A5');

		foreach($Pagos as $Pago){
			$Pdf->AddPage();
			$Pdf->Image('logo-uni.png',5,5,50);

			$Pdf->SetFont('Arial', '', 18);
			$Pdf->titleCenter('');	
			$Pdf->titleCenter('FORMATO DE PAGO - SCOTIABANK');		
			$Pdf->SetFont('Arial', '', 10);

			$Pdf->SetTextColor(255,0,0);
			$Pdf->titleCenter('Instrucciones para el PROMOTOR DE SERVICIOS');
			$Pdf->SetTextColor(0,0,0);

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'NUMERO DE CUENTA',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60,'ADMISIÓN - UNI',1,0,'L');
			
			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'DNI',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, $Postulante->NUM_IDENTIFICACION ,1,0,'L');
			
			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'CODIGO DE PAGO',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, str_pad($Postulante->ID_POSTULANTE,5,'0',STR_PAD_LEFT).$Pago->SCOTIABANK_LETRA,1,0,'L');
		
			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'NOMBRE DEL ALUMNO',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, $Postulante->PATERNO.' '.$Postulante->MATERNO.', '.$Postulante->NOMBRES ,1,0,'L');
			
			$Pdf->Ln();
			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'CONCEPTO',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, $Pago->DESCRIPCION ,1,0,'L');

			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'IMPORTE',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, 'S/. '. number_format($Pago->MONTO,2,'.',''),1,0,'L');
			
			$Pdf->Ln();
			$Pdf->Ln();

			$Pdf->SetTextColor(255,0,0);
			$Pdf->titleCenter('Instrucciones para el POSTULANTE');
			$Pdf->SetTextColor(0,0,0);
		

			$Pdf->MultiCellTextUTF8(0,'1. Verificar que los datos registrados en la parte superior sean los correctos.',0,1);
			$Pdf->MultiCellTextUTF8(0,'2. Luego de transcurridas (2) horas de realizada la inscripción, acercarse a cualquiera de las agencias Scotiabank y/o Cajeros Express (Curacao, Hiraoka, Topy Top, Maestro Home Center).',0,1);
			//$Pdf->MultiCellTextUTF8(0,'3. Podra realizar el pago apartir del dia viernes 03 de junio del 2016.',0,1);
		}
		$Pdf->Output();
		exit;
	}
	public function pagoInscripcionPdfBn(){
		$Postulante = PostulanteService::postulanteAuth();
		$Pagos = DB::select(DB::Raw('select po.ID_POSTULANTE,po.NUM_IDENTIFICACION,po.PATERNO,po.MATERNO,po.NOMBRES,pa.MONTO,pa.ID_MODALIDAD_CONCEPTO_PAGO,cp.DESCRIPCION,cp.CODIGO,cp.SCOTIABANK_LETRA,PM.ID_MODALIDAD as M
									FROM TB_POSTULANTE po 
									INNER JOIN TB_POSTULANTE_MODALIDAD PM on PM.ID_POSTULANTE = po.ID_POSTULANTE 
									INNER JOIN TB_POSTULANTE_PAGO pa on (po.ID_POSTULANTE=pa.ID_POSTULANTE)
									INNER JOIN TB_CONCEPTO_PAGO cp on (cp.ID_CONCEPTO_PAGO=pa.ID_MODALIDAD_CONCEPTO_PAGO)
									where ID_MODALIDAD_CONCEPTO_PAGO!=13 and pa.ID_POSTULANTE='.$Postulante->ID_POSTULANTE));
		//dd($Pagos);

		$Postulante = PostulanteService::postulanteAuth();		

		$Pdf = new BaseFpdf('L','mm','A5');

		foreach($Pagos as $Pago){
			$Pdf->AddPage();

			$Pdf->Image('logo-uni.png',5,5,50);

			$Pdf->SetFont('Arial', '', 18);
			$Pdf->titleCenter('');	
			$Pdf->titleCenter('FORMATO DE PAGO - BANCO FINANCIERO');		
			$Pdf->SetFont('Arial', '', 10);
			
			$Pdf->SetTextColor(255,0,0);
			$Pdf->titleCenter('Instrucciones para el PROMOTOR DE SERVICIOS');
			$Pdf->SetTextColor(0,0,0);
			//$Pdf->hr();		

			//$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'TRANSACCION',1,0,'R');
			//$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60,'9135',1,0,'L');


			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'UNIVERSIDAD',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60,'UNIV. NAC. INGENIERÍA',1,0,'L');
			
			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'PARTIDA',1,0,'R');
                        $Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60,$Pago->CODIGO,1,0,'L');

                        $Pdf->Ln();
			
			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'CONCEPTO DE PAGO',1,0,'R');
                        $Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, $Pago->DESCRIPCION ,1,0,'L');
			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'CODIGO DE PAGO',1,0,'R');
                        $Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, str_pad($Postulante->ID_POSTULANTE,5,'0',STR_PAD_LEFT).$Pago->SCOTIABANK_LETRA,1,0,'L');

                        $Pdf->Ln();

			//$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'ORDEN DE PAGO',1,0,'R');
			//$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60,'1 - Alumnos',1,0,'L');		
			
			//$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'NOMBRE DEL ALUMNO',1,0,'R');
                        $Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, $Postulante->PATERNO.' '.$Postulante->MATERNO.', '.$Postulante->NOMBRES ,1,0,'L');

                        $Pdf->Ln();


			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'DNI',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, $Postulante->NUM_IDENTIFICACION ,1,0,'L');

			$Pdf->Ln();

			$Pdf->SetFont('Arial', 'B', 10);$Pdf->CellTextCol(40,'IMPORTE',1,0,'R');
			$Pdf->SetFont('Arial', '', 10); $Pdf->CellTextUTF8Col(60, 'S/. '. number_format($Pago->MONTO,2,'.',''),1,0,'L');
			
			
			$Pdf->Ln();
			$Pdf->Ln();

			$Pdf->SetTextColor(255,0,0);
			$Pdf->titleCenter('Instrucciones para el POSTULANTE');
			$Pdf->SetTextColor(0,0,0);

			$Pdf->MultiCellTextUTF8(0,'1. Verificar que los datos registrados en la parte superior sean los correctos.',0,1);
			$Pdf->MultiCellTextUTF8(0,'2. Luego de transcurridas (2) horas de realizada la inscripción.',0,1);
			
			if($Pago->ID_MODALIDAD_CONCEPTO_PAGO == 24)
			{
				$Pdf->SetTextColor(255,0,0);
				$Pdf->MultiCellTextUTF8(0,'3. Solo pagara este recibo en caso de que no ingrese.',0,1);
				$Pdf->SetTextColor(0,0,0);
			}

		}
		$Pdf->Output();
		exit;
	}

	public function fichaInscripcionPdf(){
		$Postulante = PostulanteService::postulanteAuth();
		$PostulanteModalidadService = new PostulanteModalidadService;
		$UbigeoService = new UbigeoService;
		$PostulanteInstruccionService = new PostulanteInstruccionService;
		$PostulanteInstruccion = $PostulanteInstruccionService->existe(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		if($PostulanteInstruccion){
			$Institucion = DB::table('InstitucionEducativa')->where('InstitucionEducativaID ',$PostulanteInstruccion->ID_INSTITUCION)->first();
		}
		$Modalidades = $PostulanteModalidadService->listar(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		foreach($Modalidades as $Modalidad){
			$Modalidad->Modalidad = DB::table('Modalidad')->where('ModalidadID',$Modalidad->ID_MODALIDAD)->first();
			$Modalidad->Facultad = DB::table('Facultad')->where('FacultadID',$Modalidad->ID_FACULTAD)->first();
			$Modalidad->Especialidad1 = $Modalidad->ID_ESPECIALIDAD_1?( DB::table('Especialidad')->where('EspecialidadID',$Modalidad->ID_ESPECIALIDAD_1)->first() ):false;
			$Modalidad->Especialidad2 = $Modalidad->ID_ESPECIALIDAD_2?( DB::table('Especialidad')->where('EspecialidadID',$Modalidad->ID_ESPECIALIDAD_2)->first() ):false;
		}
		$Proceso = session('Proceso');

		$PostulanteNacimientoService = new PostulanteNacimientoService;
		$PostulanteNacimiento = $PostulanteNacimientoService->existe(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		if($PostulanteNacimiento){ 
			$_x = explode('-', $PostulanteNacimiento->F_NACIMIENTO);
			$PostulanteNacimiento->F_NACIMIENTO = $_x[2].'/'.$_x[1].'/'.$_x[0];
			$PostulanteNacimiento->Ubigeo = $UbigeoService->existe(['UbigeoID'=>$PostulanteNacimiento->UBIGEO]);
		}
		$PostulanteDomicilioService = new PostulanteDomicilioService;
		$PostulanteDomicilio = $PostulanteDomicilioService->existe(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE]);
		if($PostulanteDomicilio){
			$PostulanteDomicilio->Ubigeo = $UbigeoService->existe(['UbigeoID'=>$PostulanteDomicilio->UBIGEO]);	
		}
		$PostulanteDocumentoService = new PostulanteDocumentoService;
		$PostulanteDocumento = $PostulanteDocumentoService->primero(['ID_POSTULANTE'=>$Postulante->ID_POSTULANTE,'ID_TIPO_DOCUMENTO'=>$this->ID_DOCUMENTO_FOTO]);

		$Aulas = DB::select(DB::Raw("select ea.Codigo,ex.fecha as Fecha,ex.Orden,ex.descripcion as Descripcion 
								from Examen ex 
								inner join ExamenAula ea on (ex.ExamenID=ea.ExamenID) 
								inner join AulaAsignada aa on (aa.ExamenAulaID=ea.ExamenAulaID) 
								where aa.PostulanteID=".$Postulante->ID_POSTULANTE." and ea.ExamenID in (141,142,143,144)"));

		$Pdf = new BaseFpdf('P','mm','A4');
		$Pdf->AddPage();

		$Pdf->Image('logo-uni.png',10,5,50);
		$Pdf->Image('logo-ocad.png',150,7,45);		

		$Pdf->SetFont('Arial', 'B', 12);
		$Pdf->Cell(190,10,'',0,1);
		//$Pdf->Ln();

		$Pdf->SetFillColor(149,33,52);
		$Pdf->SetTextColor(255,255,255);
		$Pdf->Cell(150,5,'',0,0,'',1);	
		$Pdf->Cell(5,5,'',0,0,'',1);
		$Pdf->SetFillColor(169,169,169);
		$Pdf->SetTextColor(0,0,0);
		$Pdf->Cell(30,5, $Postulante->NRO_INSCRIPCION ,0,0,'C',1);
		$Pdf->SetFillColor(149,33,52);
		$Pdf->SetTextColor(255,255,255);
		$Pdf->Cell(5,5,'',0,1,'',1);
		
		$Pdf->SetFont('Arial', 'B', 30);
		$Pdf->Cell(150,12,utf8_decode('ADMISIÓN '.$Proceso->CODIGO),0,0,'R',1);
		$Pdf->Cell(5,12,'',0,0,'',1);$Pdf->Cell(30,12,'',0,0,'C',1);$Pdf->Cell(5,12,'',0,1,'',1);
		
		$Pdf->SetFont('Arial', '', 16);
		$Pdf->Cell(150,8,utf8_decode('EXCELENCIA & ÉTICA'),0,0,'R',1);
		$Pdf->Cell(5,8,'',0,0,'',1);$Pdf->Cell(30,8,'',0,0,'',1);$Pdf->Cell(5,8,'',0,1,'',1);

		$Pdf->Cell(150,5,'',0,0,'',1);
		$Pdf->Cell(5,5,'',0,0,'',1);$Pdf->Cell(30,5,'',0,0,'',1);$Pdf->Cell(5,5,'',0,1,'',1);

		$Pdf->SetFont('Arial', 'B', 18);
		$Pdf->Cell(150,8,'FICHA DEL POSTULANTE',0,0,'R',1);
		$Pdf->Cell(5,8,'',0,0,'',1);$Pdf->Cell(30,8,'',0,0,'',1);$Pdf->Cell(5,8,'',0,1,'',1);

		$Pdf->Cell(150,5,'',0,0,'',1);
		$Pdf->Cell(5,5,'',0,0,'',1);$Pdf->Cell(30,5,'',0,0,'',1);$Pdf->Cell(5,5,'',0,1,'',1);

		$Pdf->SetFillColor(255,255,255);
		$Pdf->SetTextColor(0,0,0);
		
		$Pdf->Ln();

		if($PostulanteDocumento){
			$path = $PostulanteDocumento->RUTA;
		    if(File::exists($path)){
	    		$file = File::get($path);
				//$Pdf->Image('logo-uni.png',165,25,29.95);
				$Pdf->Image($path,165,25,29.95);
			}
		}

		$Pdf->SetFont('Arial', 'B', 8); 
		$h = 6;
		//$Pdf->FontSize(8);
		
		//$Pdf->FontFamily('Arial',true);
		//$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('DNI N°:') ,0,0,'R');
		//$Pdf->setFontStyle(''); $Pdf->Cell(140,$h, $Postulante->NUM_IDENTIFICACION ,0,0);
		//$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('APELLIDOS Y NOMBRES:') ,0,0,'R');
		$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode($Postulante->PATERNO.' '.$Postulante->MATERNO.', '.$Postulante->NOMBRES),0,0);
		$Pdf->Ln();
		$Pdf->SetFont('Arial', 'B', 10);
		$h = 6;
		$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('NÚMERO DE INSCRIPCIÓN:') ,0,0,'R');
		$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode($Postulante->NRO_INSCRIPCION),0,0);
		$Pdf->SetFont('Arial', 'B', 8);
		$h = 6;
		$i=0;
		$Pdf->Ln();
		foreach($Modalidades as $Modalidad){ $i++;
			$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('MODALIDAD '.$i.':') ,0,0,'R');
			$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode($Modalidad->Modalidad->Descripcion),0,0);
			$Pdf->Ln();
			
			$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('FACULTAD:') ,0,0,'R');
			$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode('FACULTAD DE '.$Modalidad->Facultad->Descripcion),0,0);
			$Pdf->Ln();
			$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('PRIORIDAD 1:') ,1,0,'R');
			$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode($Modalidad->Especialidad1?$Modalidad->Especialidad1->Descripcion:''),1,0);
			$Pdf->Ln();
			$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('PRIORIDAD 2:') ,1,0,'R');
			$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode($Modalidad->Especialidad2?$Modalidad->Especialidad2->Descripcion:'--'),1,0);
			$Pdf->Ln();
			$Pdf->Ln();
		}

		$Pdf->setFontStyle('');
		$Pdf->Cell(50,$h, '' ,0,0,'R');
		$Pdf->Cell(30,$h, 'LUNES' ,0,0,'C');
		$Pdf->Cell(30,$h,utf8_decode('MIÉRCOLES:') ,0,0,'C');
		$Pdf->Cell(30,$h, 'VIERNES' ,0,0,'C');
		if(count($Aulas)>3){
			$Pdf->Cell(30,$h, 'JUEVES VOCACIONAL' ,0,0,'C');
		}
		$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(50,$h, utf8_decode('AULAS:') ,0,0,'R');
		$ia = 0;
		foreach($Aulas as $Aula){$ia++;
			if($ia>3){
				$Pdf->Cell(30,$h, $Aula->Codigo ,1,0,'C');
			}else{
				$Pdf->Cell(30,$h, $Aula->Codigo ,1,0,'C');
			}
		}
		//$Pdf->setFontStyle(''); $Pdf->Cell(140,$h,utf8_decode(''),0,0);
		$Pdf->Ln();

		$Pdf->Ln();

		$Pdf->SetFont('Arial', 'B', 10);
		$Pdf->SetFillColor(0,0,0); 
		$Pdf->SetTextColor(255,255,255);		
		$Pdf->Cell(0,5,utf8_decode('El ingreso al campus de la UNI es por la puerta Nº 5.'),1,1,'C',1);
		$Pdf->Cell(0,5,utf8_decode('Para rendir las tres pruebas del Examen de Admisión el horario de ingreso es de 7h00 a 8h00.'),1,1,'C',1);
		$Pdf->SetFillColor(255,255,255);
		$Pdf->SetTextColor(0,0,0);

		$Pdf->Ln();

		$Pdf->SetFont('Arial', 'B', 8);
		
		if($Institucion->GestionID=='2109') $tipoUni = "PUBLICO"; else $tipoUni = "PRIVADO";

		$Pdf->setFontStyle('B');$Pdf->Cell(38,$h, utf8_decode('INSTITUCIÓN EDUCATIVA:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(148,$h, utf8_decode($Institucion->Nombre .' - '.$tipoUni ) ,0,0); 
		$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(36,$h, utf8_decode('LUGAR DE NACIMIENTO:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(154,$h, utf8_decode( $PostulanteNacimiento?($PostulanteNacimiento->Ubigeo->Pais.' / '.$PostulanteNacimiento->Ubigeo->Departamento.' / '.$PostulanteNacimiento->Ubigeo->Provincia.' / '.$PostulanteNacimiento->Ubigeo->Distrito):'' ) ,0,0);
		$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(36,$h, utf8_decode('FECHA DE NACIMIENTO:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(154,$h, utf8_decode( $PostulanteNacimiento?$PostulanteNacimiento->F_NACIMIENTO:'' ) ,0,0);
		$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(42,$h, utf8_decode('DOCUMENTO DE IDENTIDAD:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(148,$h, utf8_decode($Postulante->NUM_IDENTIFICACION) ,0,0);
		$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(20,$h, utf8_decode('DIRECCIÓN:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(100,$h, utf8_decode($PostulanteDomicilio?$PostulanteDomicilio->DIRECCION:'') ,0,0);
		//$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(15,$h, utf8_decode('UBIGEO:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(140,$h, utf8_decode( $PostulanteDomicilio?($PostulanteDomicilio->Ubigeo->Pais.' / '.$PostulanteDomicilio->Ubigeo->Departamento.' / '.$PostulanteDomicilio->Ubigeo->Provincia.' / '.$PostulanteDomicilio->Ubigeo->Distrito):'' ) ,0,0);
		$Pdf->Ln();
		$Pdf->setFontStyle('B');$Pdf->Cell(18,$h, utf8_decode('TELÉFONO:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(32,$h, utf8_decode( $Postulante->CELULAR ) ,0,0);
		$Pdf->setFontStyle('B');$Pdf->Cell(12,$h, utf8_decode('E-MAIL:') ,0,0,'L');
		$Pdf->setFontStyle(''); $Pdf->Cell(128,$h, utf8_decode( $Postulante->CORREO ) ,0,0);
		$Pdf->Ln();
		$Pdf->hr();
		$Pdf->Ln();

		$Pdf->SetFont('Arial', 'B', 8);
		$Pdf->titleCenter('DECLARACIÓN JURADA');		
		$Pdf->setFontStyle('');

		$Pdf->Rect(160,$Pdf->getY(),35,45);
		$Pdf->Rotate(270);
		$Pdf->Text(20,$Pdf->getY()-187,'INDICE DERECHO');
		$Pdf->Rotate(0);
		$Pdf->Text(167,$Pdf->getY()+50,'HUELLA DIGITAL');
		
		$Declaracion = 'Declaro bajo juramento que toda la información registrada es auténtica y que las imágenes ';
		$Declaracion.= 'de los documentos enviados para mi inscripción como postulante al Concurso de Admisión '.$Proceso->CODIGO.' ';
		$Declaracion.= 'de la Universidad Nacional de Ingeniería, son imagen fiel de los originales, los cuales se ';
		$Declaracion.= 'encuentran en mi poder, y en caso de faltar a la verdad perderé mis derechos de postulante ';
		$Declaracion.= '(Art. 19 del Reglamento del Concurso de Admisión) y me someto a las sanciones de Ley que correspondan.';

		//$Pdf->MultiCell(140,5,utf8_decode($Declaracion),0);

		$Declaracion.= ' Así mismo, declaro no tener antecedentes policiales y de alcanzar vacante de ingreso a la UNI ';
		$Declaracion.= 'me comprometo a cumplir los requerimientos del Capítulo VII: De los ingresantes, del ';
		$Declaracion.= 'Reglamento del Concurso de Admisión '.$Proceso->CODIGO.'.';

		$Pdf->MultiCell(145,5,utf8_decode($Declaracion),0);
		
		$Pdf->Ln();
		$Pdf->Ln();

		//$Pdf->Cell(40,5,'',0,0,'',0);
		//$Pdf->Cell(180,5);
		$Pdf->SetXY(12,265);
		$Pdf->Cell(50,5,'Firma del Postulante','T',1,'C',0);
		
		//$Pdf->Cell(40,5,'',0,0,'',0);
		//$Pdf->Cell(80,5);
		$Pdf->SetXY(90,265);
		$Pdf->Cell(50,5,'Firma del Apoderado','T',0,'C',0);
		
		//$Pdf->Cell(0,15);
		$Pdf->SetXY(12,270);
		$Pdf->setFontStyle('B');$Pdf->Cell(38,$h, utf8_decode('* En caso de ser menor de edad debe firmar el Apoderado.') ,0,0,'L');
		//$Pdf->Ln();
		//$Pdf->Ln();
		
		// Cambios desde aqui
		$Pdf->SetXY(5,220);
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		$Pdf->Ln();
		
		$Pdf->SetFont('Arial', 'B', 14);
		$Pdf->titleCenter('INSTRUCCIÓN PARA EL DÍA DEL EXAMEN');		
		$Pdf->setFontStyle('');
		
		$Pdf->Ln();
		$Pdf->SetFont('Arial', '', 12);
		$Declaracion = 'El postulante podrá ingresar al campus universitario por la puerta N° 5, a partir de las ';
		$Declaracion.= '07h00 hasta las 08h00 y se dirigirá inmediatamente al aula donde rendirá el examen.';
		$Declaracion.= ' No se permitirá el ingreso al campus después de las 08h00.';
		
		$Pdf->MultiCell(180,5,utf8_decode($Declaracion),0);
		$Pdf->Ln();
		
		$Declaracion = 'Para ingresar al campus los días que rendirá las pruebas, el postulante se identificará ';
		$Declaracion.= 'con su Ficha de Postulante, además, debe portar otro documento de identidad y sólo ';
		$Declaracion.= 'debe llevar consigo lápiz 2B, borrador blanco, tajador, regla y escuadras (No debe ';
		$Declaracion.= 'portar lapiceros). Para las pruebas de Matemática, Física y Química se le proporcionará ';
		$Declaracion.= 'una calculadora para ser usada durante el desarrollo de estas pruebas.';
		$Declaracion.= ' El postulante no debe portar bolsos, carteras, teléfono celular, aparatos electrónicos,  ';
		$Declaracion.= 'beeper, calculadora, dispositivos de almacenamiento de información o música, reloj, ';
		$Declaracion.= 'ganchos, brazaletes, medallas, medallones, binchas, anillos grandes, etc. De hacerlo,  ';
		$Declaracion.= 'no se le permitirá el ingreso al campus. NO SE OFRECERÁ SERVICIO DE CUSTODIA DE  ';
		$Declaracion.= 'PRENDAS. ';
		$Declaracion.= 'A los padres de familia o familiares que acompañen al postulante, no se les permitirá el ';
		$Declaracion.= 'ingreso al campus. Tener en consideración que el desplazamiento del postulante desde ';
		$Declaracion.= 'la puerta de ingreso a cualquier sector, donde se ubique el aula de examen, le ';
		$Declaracion.= 'demorará a pie un máximo de veinte minutos.';
		
		$Pdf->MultiCell(180,5,utf8_decode($Declaracion),0);
		
		$Pdf->Output();
		exit;
	}
}
