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
			<div class="col-sm-8 col-sm-offset-2">
				@if( session('success') )
				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>{{ session('success') }}</strong>
				</div>
				@endif
				@if( session('warning') )
				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>{{ session('warning') }}</strong>
				</div>
				@endif
				@if( session('danger') )
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>{{ session('danger') }}</strong>
				</div>
				@endif	
				<div class="card card-bordered">					
					<div class="card-head card-head-xs style-primary text-center">
						<header>Tu Postulación</header>
					</div>
					<div class="card-body style-default-bright no-padding">
						<ul class="list">
							@foreach($ObjetoProcesoLista as $ObjetoProceso)
							{!! Form::open(['route'=>'gestor-flujo.proceso.resolver','method'=>'POST']) !!}
							<input type="hidden" name="ID_OBJETO_PROCESO" value="{{ $ObjetoProceso->ID_OBJETO_PROCESO }}">
							<li class="tile">														
								<a class="tile-content ink-reaction">
									<div class="tile-text">
										<span class="text-sm">{{ $ObjetoProceso->NOMBRE_PROCESO }}</span>
										<!--<small></small>-->
									</div>
								</a>
								@if($ObjetoProceso->FECHA_INICIO)
								<a class="btn btn-sm btn-default">{{ substr($ObjetoProceso->FECHA_INICIO,0,19) }}</a>
								@endif
								@if(!$ObjetoProceso->FECHA_FIN)
								<a class="btn btn-resolver btn-flat ink-reaction text-primary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Resolver">
									<i class="fa fa-play"></i>
								</a>
								@else
								<a class="btn btn-flat ink-reaction" disabled>
									<i class="md md-done-all"></i>
								</a>					
								@endif
							</li>
							{!! Form::close() !!}							
							@endforeach
						</ul>

					</div><!--end .card-body -->
				</div>
			</div>
		</div>
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
$(function(){
	$('.btn-resolver').click(function(){
		$(this).closest('form').submit();
	});
});
</script>
@stop
