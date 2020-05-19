<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReservacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

use App\Mail\ReservacionExitosa;
use App\Mail\CambioReservacion;
use App\Mail\CancelarReservacion;
use App\Models\Reservacion;
use App\Models\Habitacion;
use App\Models\Promocion;
use \App\Models\Cliente;
use \App\Models\MetodoPago; 
use \App\Models\ServicioAdicional; 
use \App\Models\TipoHabitacion; 
use DB;

/**
 * Class ReservacionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReservacionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }


    public function setup()
    {
        $this->crud->setModel('App\Models\Reservacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reservacion');
        $this->crud->setEntityNameStrings('Reservación', 'Reservaciones');
        $this->crud->setEditView('/vendor/crud/edit_reservacion');
        $this->crud->setCreateView('/vendor/crud/create_reservacion');
    }

    protected function setupListOperation()
    {
      $this->crud->removeButton('show');
      $this->crud->removeButton('delete');
      $this->crud->addButtonFromView('line', 'cancelarReservacion', 'cancelarReservacion', 'end');
      
      $this->crud->orderBy(DB::raw('ABS(DATEDIFF(reservaciones.fecha_entrada, NOW()))'));//ORDENADO POR FECHA MAS PROXIMA A HOY

      $this->crud->addColumn([
        'name' => 'cliente.nombre', // The db column name
        'label' => "Cliente", // Table column heading
        'searchLogic' => function ($query, $column, $searchTerm) {
            $query->orWhereHas('cliente', function($q) use ($searchTerm){
                $q->where('nombre', 'like', '%'.$searchTerm.'%');
            });
        }
      ]);

      $this->crud->addColumn([
        'name' => 'habitacion.numero', // The db column name
        'label' => "Habitación", // Table column heading
        'searchLogic' => function ($query, $column, $searchTerm) {
            $query->orWhereHas('habitacion', function($q) use ($searchTerm){
                $q->where('numero', 'like', '%'.$searchTerm.'%');
            });
        }
     ]);

     $this->crud->addColumn([
      'name' => 'habitacion.tipoHabitacion.nombre', // The db column name
      'label' => "Tipo de habitación", // Table column heading
      'searchLogic' => function ($query, $column, $searchTerm) {
          $query->orWhereHas('habitacion.tipoHabitacion', function($q) use ($searchTerm){
              $q->where('nombre', 'like', '%'.$searchTerm.'%');
          });
      }
      ]);


     $this->crud->addColumn([
        'name' => "fecha_entrada", // The db column name
        'label' => "Fecha De Entrada", // Table column heading
        'type' => "datetime",
         // 'format' => 'l j F Y H:i:s', // use something else than the base.default_datetime_format config value
     ]);

     $this->crud->addColumn([
        'name' => "fecha_salida", // The db column name
        'label' => "Fecha De Salida", // Table column heading
        'type' => "datetime",
         // 'format' => 'l j F Y H:i:s', // use something else than the base.default_datetime_format config value
     ]);

     $this->crud->addColumn([
        'name' => 'status_reservacion',
        'label' => 'Status De La Reservación',
        'type' => 'boolean',
        'options' => [0 => 'Inactiva', 1 => 'Activa'],
      ]);

      $this->crud->addColumn([
          'name' => 'costo_total',
          'label' => 'Costo Total',
          'type' => 'text',
          'searchLogic' => function ($query, $column, $searchTerm) {
              $query->orWhere('costo_total', 'like', '%'.$searchTerm.'%');
          }
      ]);

      $this->crud->addColumn([
        'name' => 'metodoPago.nombre', // The db column name
        'label' => "Métodos De Pago", // Table column heading
        'searchLogic' => function ($query, $column, $searchTerm) {
            $query->orWhereHas('metodoPago', function($q) use ($searchTerm){
                $q->where('nombre', 'like', '%'.$searchTerm.'%');
            });
        }
      ]);

      $this->crud->addColumn([
        'name' => 'promocion.nombre', // The db column name
        'label' => "Promociones", // Table column heading
        'searchLogic' => function ($query, $column, $searchTerm) {
            $query->orWhereHas('promocion', function($q) use ($searchTerm){
                $q->where('nombre', 'like', '%'.$searchTerm.'%');
            });
        }
      ]);

      $this->crud->addFilter([
        'type'  => 'date',
        'name'  => 'fecha_entrada',
        'label' => 'Fecha De Entrada',
      ],
      false,
      function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('whereDate', 'fecha_entrada', $value);
      });

      $this->crud->addFilter([
        'type'  => 'date',
        'name'  => 'fecha_salida',
        'label' => 'Fecha De Salida',
      ],
      false,
      function ($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('whereDate', 'fecha_salida', $value);
      });

      $this->crud->addFilter([
        'name'  => 'tipoHabitacion',
        'type'  => 'select2',
        'label' => 'Tipo De Habitación'
      ], function () {
       return TipoHabitacion::all()->pluck('nombre', 'id')->toArray();
      }, function ($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'habitacion.tipoHabitacion', function($q) use ($value){
              $q->where('id', $value);
            });
      });

      // $this->crud->addFilter([
      //   'name'  => 'status',
      //   'type'  => 'select2',
      //   'label' => 'Status De La Reservación'
      // ], function () {
      //   return [
      //     1 => 'Activa',
      //     2 => 'Inactiva',
      //   ];
      // }, function ($value) { // if the filter is active
      //       $this->crud->addClause('where', 'status_reservacion', $value);
      // });

      // $this->crud->addFilter([
      //   'type' => 'text',
      //   'name' => 'costo_total',
      //   'label'=> 'Costo Total'
      // ], 
      // false, 
      // function($value) { // if the filter is active
      //    $this->crud->addClause('where', 'costo_total', 'like', '%'.$value.'%');
      // });

      $this->crud->addFilter([
        'name' => 'habitacion',
        'type' => 'select2_multiple',
        'label'=> '# Habitación'
      ], function() {
          return Habitacion::pluck('numero', 'id')->toArray();
      }, function($values) { // if the filter is active
          $this->crud->addClause('whereIn', 'habitacion_id', json_decode($values));
      });

      $this->crud->addFilter([
        'name' => 'cliente_id',
        'type' => 'select2',
        'label'=> 'Clientes'
      ], function() {
          return Cliente::all()->pluck('nombre', 'id')->toArray();
      }, function($value) { // if the filter is active
          $this->crud->addClause('where', 'cliente_id', 'like', '%'.$value.'%');
      });

      $this->crud->addFilter([
        'name' => 'metodo_pago_id',
        'type' => 'select2',
        'label'=> 'Metodos De Pago'
      ], function() {
          return MetodoPago::all()->pluck('nombre', 'id')->toArray();
      }, function($value) { // if the filter is active
          $this->crud->addClause('where', 'metodo_pago_id', $value);
      });

      $this->crud->addFilter([
        'name' => 'promocion_id',
        'type' => 'select2',
        'label'=> 'Promociones'
      ], function() {
          return Promocion::all()->pluck('nombre', 'id')->toArray();
      }, function($value) { // if the filter is active
          $this->crud->addClause('where', 'promocion_id', $value);
      });
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ReservacionRequest::class);

        $this->crud->addField([   // date_range
          'name' => ['fecha_entrada', 'fecha_salida'], // db columns for start_date & end_date
          'label' => 'Tiempo de estadía',
          'type' => 'date_range_reservacion',
          'default' => [Carbon::now(), Carbon::now()->addDays(7)], // default values for start_date & end_date
          // OPTIONALS
          'date_range_options' => [
              // options sent to daterangepicker.js
              'timePicker' => true,
              'locale' => ['format' => 'DD/MM/YYYY HH:mm'],
          ]
        ]);

        $this->crud->addField([
          'name' => 'cantidad_adultos',
          'label' => 'Cantidad de adultos (1 - 8) Precio por adulto: ' . env('PRECIO_POR_ADULTO', 400),
          'type' => 'number',
          'attributes' => ['min' => 1, 'max' => 8],
          'wrapperAttributes' => [
            'class' => 'form-group col-md-6'
          ],
        ]);

        $this->crud->addField([
          'name' => 'cantidad_ninos',
          'label' => 'Cantidad de niños (0 - 8) Precio por niño: ' . env('PRECIO_POR_NINO', 250),
          'type' => 'number',
          'attributes' => ['min' => 0, 'max' => 8],
          'wrapperAttributes' => [
            'class' => 'form-group col-md-6'
          ],
        ]);

        $this->crud->addField([  // Select2
            'label' => "# Habitación <span id='tipo_habitacion_label_id'></span>",
            'type' => 'select2',
            'name' => 'habitacion_id', // the db column for the foreign key
            'entity' => 'Habitacion', // the method that defines the relationship in your Model
            'attribute' => 'numero', // foreign key attribute that is shown to user
            'options'   => (function ($query) {
              // return [];
                return $query->where('status_id', '!=', '2')->with('tipoHabitacion')->get();
            }), // force the related options to be a custom query, instead of all();
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Cliente",
            'type' => 'select2',
            'name' => 'cliente_id', // the db column for the foreign key
            'entity' => 'Cliente', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Método De Pago",
            'type' => 'select2',
            'name' => 'metodo_pago_id', // the db column for the foreign key
            'entity' => 'MetodoPago', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Promociones",
            'type' => 'select2',
            'name' => 'promocion_id', // the db column for the foreign key
            'entity' => 'Promocion', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            'options'   => (function ($query) {
              return $query->where('fecha_inicio', '<=', now())->where('fecha_final', '>=', now())->get();
            }), 
          ]);

        $this->crud->addField([
            'name' => 'costo_total',
            'label' => 'Total',
            'type' => 'text',
            'wrapperAttributes' => [
              'class' => 'form-group col-md-6'
            ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            'attributes' => [
              'readonly' => 'readonly',
            ],
        ]);

        $this->crud->addField([    // Select2Multiple = n-n relationship (with pivot table)
          'label'     => "Servicios adicionales",
          'type'      => 'select2_multiple',
          'name'      => 'serviciosAdicionales', // the method that defines the relationship in your Model
          'entity'    => 'serviciosAdicionales', // the method that defines the relationship in your Model
          'attribute' => 'nombre', // foreign key attribute that is shown to user
          'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
          // 'select_all' => true, // show Select All and Clear buttons?
     
          // optional
          'model'     => "App\Models\ServicioAdicional", // foreign key model
        ]);
        
    }

    protected function setupUpdateOperation()
    {
      $reservacion = Reservacion::find($this->crud->request->route('id'));

      if($reservacion){
        if($reservacion->fecha_entrada > Carbon::now()->addDays(3)){//SE PUEDE EDITAR POR COMPLETO TODA LA RESERVACION SI LA FECHA DE ENTRADA ES MAYOR A 3 DIAS DE HOY
          
          $this->crud->addField([  // Select2
            'label' => "# Habitación <span id='tipo_habitacion_label_id'></span>",
            'type' => 'select2',
            'name' => 'habitacion_id', // the db column for the foreign key
            'entity' => 'Habitacion', // the method that defines the relationship in your Model
            'attribute' => 'numero', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
          ]);

          $this->setupCreateOperation();
          
        }
        else{
          $this->crud->addField([   // CustomHTML
            'name' => 'reservacion_actual',
            'type' => 'custom_html',
            'value' => "<h2>Información de la reservación</h2>
                        <hr>
                        <div class='w-100 d-flex flex-wrap justify-content-between'>
                          <span id='cliente' class='w-50 mb-2'><strong>Cliente: </strong>" . ($reservacion->cliente ? $reservacion->cliente->nombre : '') . "</span>
                          <span id='habitacion' class='w-50 mb-2'><strong># Habitación: </strong>" . ($reservacion->habitacion ? $reservacion->habitacion->numero : '') . "</span>
                          <span id='fecha_entrada' class='w-50 mb-2'><strong>Fecha de entrada: </strong>" . ($reservacion->fecha_entrada ? $reservacion->fecha_entrada->format('d/m/Y H:i') : '') . "Hrs</span>
                          <span id='fecha_salida' class='w-50 mb-2'><strong>Fecha de salida: </strong>" . ($reservacion->fecha_salida ? $reservacion->fecha_salida->format('d/m/Y H:i') : ''). "Hrs</span>
                          <span id='tipo_habitacion' class='w-50 mb-2'><strong>Tipo de habitación: </strong>" . ($reservacion->habitacion ? ($reservacion->habitacion->tipoHabitacion ? $reservacion->habitacion->tipoHabitacion->nombre : '') : '') . "</span>
                          <span id='promocion' class='w-50 mb-2'><strong>Promoción: </strong>" . ($reservacion->promocion ? $reservacion->promocion->nombre : 'Ninguna promoción aplicada') . "</span>
                          <span id='costo_total' class='w-50 mb-2'><strong>Costo total: </strong>" . ($reservacion->costo_total ? number_format($reservacion->costo_total, 2) : '') . "</span>
                          <span id='servicios_adicionales' class='w-50 mb-2'><strong>Servicios Adicionales: </strong>" . $reservacion->getServiciosAdicionales() . "</span>
                        </div>"
          ]);
        }
      }      
    }

    public function store()
    {
        $costo_total = $this->crud->request->request->get('costo_total');

        $promocion = $this->crud->request->request->get('promocion_id');

        if($promocion){
          $promocion = Promocion::find($promocion);

          if($promocion->fecha_final < Carbon::now()){
            return redirect()->back()->withErrors(['La fecha para la promoción ha caducado, intente de nuevo la reservación'])->withInput((array)$this->crud->request->all());
          }
        }

        if($costo_total){
            $this->crud->request->request->set('costo_total', str_replace(',', '', $costo_total));
        }
        $this->crud->request->request->set('status_reservacion', 1);
        // do something before validation, before save, before everything; for example:
        // $this->crud->request->request->add(['author_id'=> backpack_user()->id]);
        // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
        // $this->crud->request->request->remove('password_confirmation');
        // $this->crud->removeField('password_confirmation');

        $response = $this->traitStore();
        // do something after save
        $reservacion = Reservacion::find($this->crud->entry->id);

        if($reservacion){
            $cliente = $reservacion->cliente;
            Mail::to($cliente->email)->send(new ReservacionExitosa($reservacion, $cliente, backpack_user()));
        }

        return $response;
    }

    public function update()
    {
        $reservacion = Reservacion::find($this->crud->request->id);
        $fecha_entrada = $this->crud->request->request->get('fecha_entrada');
        $fecha_salida = $this->crud->request->request->get('fecha_salida');
        $habitacion = $this->crud->request->request->get('habitacion_id');
        $costo_total = $this->crud->request->request->get('costo_total');
        $metodo_pago = $this->crud->request->request->get('metodo_pago_id');
        $serviciosAdicionales = $this->crud->request->request->get('serviciosAdicionales');

        $this->crud->request->request->remove('reservacion_actual');

        $promocion = $this->crud->request->request->get('promocion_id');

        if($promocion){
          $promocion = Promocion::find($promocion);

          if($promocion->fecha_final < Carbon::now()){
            return redirect()->back()->withErrors(['La fecha para la promoción ha caducado, intente de nuevo la reservación'])->withInput((array)$this->crud->request->all());
          }
        }

        if($costo_total){
          $costo_total = str_replace(',', '', $costo_total);
            $this->crud->request->request->set('costo_total', $costo_total);
        }

        $cambios = array();
        
        if($fecha_entrada && $reservacion->fecha_entrada != $fecha_entrada){
          $cambios['Fecha de entrada: '] = 'Cambió de ' . $reservacion->fecha_entrada . ' a ' . $fecha_entrada;
        }

        if($fecha_salida && $reservacion->fecha_salida != $fecha_salida){
          $cambios['Fecha de salida: '] = 'Cambió de ' . $reservacion->fecha_salida . ' a ' . $fecha_salida;
        }

        if($habitacion && $reservacion->habitacion_id != $habitacion && $reservacion->habitacion){
          $cambios['# Habitación: '] = 'Cambió de ' . $reservacion->habitacion->numero . ' a ' . Habitacion::find($habitacion)->numero;
        }

        if($metodo_pago && $reservacion->metodo_pago_id != $metodo_pago && $reservacion->metodoPago){
          $cambios['Método de pago: '] = 'Cambió de ' . $reservacion->metodoPago->nombre . ' a ' . MetodoPago::find($metodo_pago)->nombre;
        }

        if($costo_total && $reservacion->costo_total && $reservacion->costo_total != $costo_total){
          $cambios['Costo total: '] = 'Cambió de ' . number_format($reservacion->costo_total, 2) . ' a ' . number_format($costo_total, 2);
        }

        if($serviciosAdicionales && count($serviciosAdicionales) > 0){
          $serv = ServicioAdicional::whereIn('id', $serviciosAdicionales)->get();
          if(count($reservacion->serviciosAdicionales) == 0){
            $ser = '';
            foreach($serv as $sa){
              $ser .= $sa->nombre . " ";
            }
  
            if($ser){
              $cambios['Servicios adicionales: '] = 'Cambió de no tener a ' . $ser;
            }
          }
          else{
            $serA = '';
            foreach($reservacion->serviciosAdicionales as $sa){
              $serA .= $sa->nombre . " ";
            }

            $ser = '';
            foreach($serv as $sa){
              $ser .= $sa->nombre . " ";
            }

            $cambios['Servicios adicionales: '] = 'Cambió de '.$serA.' a '.$ser;
          }
        }
        else if(count($reservacion->serviciosAdicionales) > 0 && $reservacion->fecha_entrada > Carbon::now()->addDays(3)){
          $ser = '';
          foreach($reservacion->serviciosAdicionales as $sa){
            $ser .= $sa->nombre . " ";
          }
          $cambios['Servicios adicionales: '] = 'Cambió de '.$ser.' a no tener ningún servicio adicional';
        }
        
        // do something before validation, before save, before everything; for example:
        // $this->crud->request->request->add(['author_id'=> backpack_user()->id]);
        // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
        // $this->crud->request->request->remove('password_confirmation');
        // $this->crud->removeField('password_confirmation');
        $response = $this->traitUpdate();
        // do something after save

        if(count($cambios) > 0 && $reservacion && $reservacion->cliente){
          $cliente = $reservacion->cliente;
          Mail::to($cliente->email)->send(new CambioReservacion($reservacion, $cliente, backpack_user(), $cambios));
        }

        return $response;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $reservacion = Reservacion::find($id);

        if($reservacion){
            $cliente = $reservacion->cliente;
            Mail::to($cliente->email)->send(new CancelarReservacion($reservacion, $cliente, backpack_user()));
        }

        return $this->crud->delete($id);
    }

    public function calcularTotalFechas(Request $request){
      
        $fecha_entrada = $request->get('fecha_entrada');
        $fecha_salida = $request->get('fecha_salida');
        $habitacion = Habitacion::find($request->get('habitacion'));
        $promocion = Promocion::find($request->get('promocion'));
        $cantidad_adultos = $request->get('cantidad_adultos');
        $cantidad_ninos = $request->get('cantidad_ninos');
        $serviciosAdicionales = [];
        $total = 0;

        if($request->get('serviciosAdicionales')){
          $serviciosAdicionales = ServicioAdicional::whereIn('id', $request->get('serviciosAdicionales'))->get();
        }

        if($fecha_entrada && $fecha_salida && $habitacion && $cantidad_adultos){
            $fecha_entrada = new Carbon($fecha_entrada);
            $fecha_salida = new Carbon($fecha_salida);

            $tipo_habitacion = $habitacion->tipoHabitacion;

            $dias = $fecha_entrada->diffInDays($fecha_salida, false);
            $total = $tipo_habitacion->costo * ($dias === 0 ? 1 : $dias);

            foreach($serviciosAdicionales as $sa){
              $total += $sa->costo;
            }

            $total += (env('PRECIO_POR_ADULTO', 400) * $cantidad_adultos) + (env('PRECIO_POR_NINO', 250) * $cantidad_ninos);

            if($promocion){
              $total -= (($total / 100) * $promocion->descuento);
            }
        }

        return $total;
    }

    public function habitacionesDisponibles(Request $request){

      $fecha_entrada = $request->get('fecha_entrada');
      $fecha_salida = $request->get('fecha_salida');
      $cantidad_adultos = $request->get('cantidad_adultos');
      $cantidad_ninos = $request->get('cantidad_ninos');

      if($fecha_entrada && $fecha_salida && $cantidad_adultos){
        $fecha_entrada = new Carbon($fecha_entrada);
        $fecha_salida = new Carbon($fecha_salida);
        $fecha_entrada->subHours(3);

        $reservaciones = Reservacion::where('fecha_salida', '>=', $fecha_entrada)->pluck('habitacion_id');

        if(count($reservaciones) > 0){
          $habitaciones = Habitacion::whereNotIn('id', $reservaciones)->get();
        }
        else{
          $habitaciones = Habitacion::all();
        }
        
        return $habitaciones;
      }

      return [];
    }
}
