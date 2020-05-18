@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => backpack_url('dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.edit') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.edit').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="{{ $crud->getEditContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route.'/'.$entry->getKey()) }}"
				@if ($crud->hasUploadFields('update', $entry->getKey()))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  {!! method_field('PUT') !!}

		  	@if ($crud->model->translationEnabled())
		    <div class="mb-2 text-right">
		    	<!-- Single button -->
				<div class="btn-group">
				  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
					  	<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a>
				  	@endforeach
				  </ul>
				</div>
		    </div>
		    @endif
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', ['fields' => $crud->fields(), 'action' => 'edit'])
		      @else
		      	@include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'edit'])
		      @endif

            @include('crud::inc.form_save_buttons')
		  </form>
	</div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

<script>
	$( document ).ready(function() {
		var cantidad = $("[name='costo_total'");
		var tiempo_estadia = $("[name='date_fecha_entrada_fecha_salida']");
		var habitacion = $("[name='habitacion_id']");
		var promocion = $("[name='promocion_id']");
		var serviciosAdicionales = $("[name='serviciosAdicionales[]']");
		var fecha_entrada = $(".datepicker-range-start");
		var fecha_salida = $(".datepicker-range-end");
		var cantidad_adultos = $("[name='cantidad_adultos']");
		var cantidad_ninos = $("[name='cantidad_ninos']");

		tiempo_estadia.on('apply.daterangepicker hide.daterangepicker', function(e, picker){
			fecha_entrada.val( picker.startDate.format('YYYY-MM-DD HH:mm:ss') );
			fecha_salida.val( picker.endDate.format('YYYY-MM-DD HH:mm:ss') );
			calcularTotalPorFechas();
			habitacionesDisponibles();
		});

		cantidad_adultos.add(cantidad_ninos).add(habitacion).add(promocion).add(serviciosAdicionales).on('change', function(){
			if($(this).attr('name') == cantidad_adultos.attr('name') || $(this).attr('name') == cantidad_ninos.attr('name')){
				habitacionesDisponibles();
			}
			calcularTotalPorFechas();
		});

		function habitacionesDisponibles(){
			if(fecha_entrada.val() && fecha_salida.val() && cantidad_adultos.val() && cantidad_ninos.val()){
				$.ajax({
					url: '/admin/reservacion/habitacionesDisponibles',
					type: 'POST',
					data: {fecha_entrada: fecha_entrada.val(), fecha_salida: fecha_salida.val(), cantidad_adultos: cantidad_adultos.val(), cantidad_ninos: cantidad_ninos.val()},
					success: function(result){
						habitacion[0].options.length = 0;
						let opciones = [];
						for(var i = 0; i < result.length; i++){
							habitacion.append("<option value='" + result[i].id + "'>" + result[i].numero + "</option>");
						}
						calcularTotalPorFechas(habitacion.val(), fecha_entrada.val(), fecha_salida.val(), promocion.val());
					},
					error: function(result){
						console.log('error: ' + result);
					},
				});
			}
		}

		function calcularTotalPorFechas(){
			if(habitacion.val() && fecha_entrada.val() && fecha_salida.val() && cantidad_adultos.val() && cantidad_ninos.val()){
				$.ajax({
					url: '/admin/reservacion/calcularTotalFechas',
					type: 'POST',
					data: {habitacion: habitacion.val(), fecha_entrada: fecha_entrada.val(), fecha_salida: fecha_salida.val(), promocion: promocion.val(), serviciosAdicionales: serviciosAdicionales.val(), cantidad_ninos: cantidad_ninos.val(), cantidad_adultos: cantidad_adultos.val()},
					success: function(result){
						// console.log('exito: ' + result);
						if(!isNaN(result)){
							cantidad.val(Number.parseFloat(result).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
						}
					},
					error: function(result){
						console.log('error: ' + result);
					},
				});
			}
		}
	});
</script>
