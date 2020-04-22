<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <a id="btnCalcularTotalReservacion" onclick="calcularTotalReservacion(this)" class="btn btn-primary btn-sm mb-1"><i class="fa fa-calculator"></i>{!! $field['label'] !!}</a>
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
      <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
      <script>
          var cantidad = $("[name='{{ $field['name'] }}'");
          var fecha_entrada = $("[name='fecha_entrada']");
          var fecha_salida = $("[name='fecha_salida']");
          var habitacion = $("[name='habitacion_id']");
          var promocion = $("[name='promocion_id']");

          function calcularTotalReservacion(button){
            calcularTotalPorFechas(habitacion.val(), fecha_entrada.val(), fecha_salida.val(), promocion.val());
          }
      
          function calcularTotalPorFechas(habitacion, fecha_entrada, fecha_salida, promocion){
            $.ajax({
                url: '/admin/reservacion/calcularTotalFechas',
                type: 'POST',
                data: {habitacion: habitacion, fecha_entrada: fecha_entrada, fecha_salida: fecha_salida, promocion: promocion },
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
        </script>
    @endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
