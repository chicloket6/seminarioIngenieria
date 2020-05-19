@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')

<div class="row">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
			  {!! csrf_field() !!}

		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
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
		var cantidad_adultos = $("[name='cantidad_adultos']");
		var cantidad_ninos = $("[name='cantidad_ninos']");
		var fecha_entrada = $(".datepicker-range-start");
		var fecha_salida = $(".datepicker-range-end");
		var serviciosAdicionales = $("[name='serviciosAdicionales[]']");
		var tipos_habitaciones = '{!! json_encode(App\Models\TipoHabitacion::all()) !!}';
		var habitaciones = '{!! json_encode(App\Models\Habitacion::all()) !!}';

		if(tipos_habitaciones){
			tipos_habitaciones = JSON.parse(tipos_habitaciones);
		}

		if(habitaciones){
			habitaciones = JSON.parse(habitaciones);
		}

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

			if($(this).attr('name') == habitacion.attr('name')){
				actualizarTipoHabitacion(habitaciones.find(x => x.id === Number($(this).val())).tipo_habitacion_id);
			}
			calcularTotalPorFechas();
		});

		function actualizarTipoHabitacion(id, actualizarLabel = true){
			if(tipos_habitaciones.length > 0){
				let tipo_h = tipos_habitaciones.find(x => x.id === id);

				if(tipo_h){
					if(actualizarLabel){
						$("#tipo_habitacion_label_id").text(" - " + tipo_h.nombre + "($ " + tipo_h.costo + " / día)");
					}
					return tipo_h;
				}
			}
		}

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
							if(i === 0){
								actualizarTipoHabitacion(result[i].tipo_habitacion_id);
							}
							habitacion.append("<option value='" + result[i].id + "'>" + result[i].numero + " - " + actualizarTipoHabitacion(result[i].tipo_habitacion_id, false).nombre + "</option>");
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
