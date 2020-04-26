<!-- select2 -->
@php
    $current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
    $entity_model = $crud->getRelationModel($field['entity'],  - 1);

    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
@endphp

<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <a id="btnDisponibilidadHabitaciones" onclick="disponibilidadHabitaciones(this)" class="btn btn-primary btn-sm mb-1"><i class="fa fa-calculator"></i>Disponibilidad</a>

    <select
        name="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2Element"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
        >

        @if ($entity_model::isColumnNullable($field['name']))
            <option value="">-</option>
        @endif

        @if (count($options))
            @foreach ($options as $option)
                @if($current_value == $option->getKey())
                    <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
                @endif
            @endforeach
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
        @if (app()->getLocale() !== 'en')
        <script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
        @endif
        <script>
            var fecha_entrada = $("[name='fecha_entrada']");
            var fecha_salida = $("[name='fecha_salida']");
            var habitacion = $("[name='habitacion_id']");

            function bpFieldInitSelect2Element(element) {
                // element will be a jQuery wrapped DOM node
                if (!element.hasClass("select2-hidden-accessible")) {
                    element.select2({
                        theme: "bootstrap"
                    });
                }
            }
            function disponibilidadHabitaciones(button){
            obtenerDisponibilidadHabitaciones(habitacion.val(), fecha_entrada.val(), fecha_salida.val());
            }
        
            function obtenerDisponibilidadHabitaciones(hab, fecha_entrada, fecha_salida){
                $.ajax({
                    url: '/admin/reservacion/obtenerDisponibilidadHabitaciones',
                    type: 'POST',
                    data: {habitacion: hab, fecha_entrada: fecha_entrada, fecha_salida: fecha_salida},
                    success: function(result){
                        // console.log('exito: ' + result);
                        // console.log(habitacion);
                        habitacion[0].options.length = 0;
                        let opciones = [];
                        for (var i = 0; i < result.length; i++) {
                            opciones.push("<option value='" + result[i].id + "'>" + result[i].numero + "</option>");
                        }

                        for(var i = 0; i < opciones.length; i++){
                            habitacion.append(opciones[i]);
                        }
                            
                    },
                    error: function(result){
                        console.log('error: ' + result);
                    },
                });
            }

        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
