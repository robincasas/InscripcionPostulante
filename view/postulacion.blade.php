@extends('AccesoUsuario::auth')

@section('head_tags')
{!! Html::style('assets/css/theme-default/libs/wizard/wizard.css?1425466601') !!}
@stop

@section('main-menu')
	@parent
@stop

@section('content')
<section>
	<!--
	<div class="section-header">
		<ol class="breadcrumb">
			<li class="active">Form basic</li>
		</ol>
	</div>
	-->
	<div class="section-body contain-lg">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
				<div class="card">
					<div class="card-head card-head-xs style-primary">										
						<header>Elija el Proceso de Admisión al que desea Postular</header>
					</div>
					<div class="card-body style-default-bright">
						{!! Form::open(['route' => 'postulacion.iniciar','method'=>'POST']) !!}
						<div class="form-group row">						
							<!--<label for="postulacion" class="col-sm-2 control-label">Select</label>-->
							<div class="col-sm-12">								
								<select id="postulacion" name="ID_POSTULACION_TIPO" class="form-control">
									@foreach($PostulacionTipoLista as $PostulacionTipo)
									<option value="{{ $PostulacionTipo->ID_POSTULACION_TIPO }}">{{ $PostulacionTipo->NOMBRE_POSTULACION }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-4 col-sm-offset-4">	
								<button type="submit" class="btn btn-block btn-primary">Iniciar</button>
							</div>
						</div>
						{!! Form::close() !!}
					</div><!--end .card-body -->
				</div>
				
			</div>
		</div>			
		<!-- END INTRO -->
	</div><!--end .section-body -->
</section>
@stop

@section('body_scripts')
{!! Html::script('assets/js/libs/inputmask/jquery.inputmask.bundle.min.js') !!}
{!! Html::script('assets/js/libs/jquery-validation/dist/jquery.validate.min.js') !!}
{!! Html::script('assets/js/libs/jquery-validation/dist/localization/messages_es.js') !!}
{!! Html::script('assets/js/libs/wizard/jquery.bootstrap.wizard.min.js') !!}

{!! Html::script('assets/jform/jquery.form.js') !!}

<script>
function evaluaFinalizar(){
	
}
$(function(){
	
});
</script>
@stop
