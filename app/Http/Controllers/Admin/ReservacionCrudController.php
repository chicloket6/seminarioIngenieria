<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReservacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

use App\Mail\ReservacionExitosa;
use App\Models\Reservacion;
use App\Models\Habitacion;
use App\Models\Promocion;
use \App\Models\Cliente;
use \App\Models\MetodoPago; 

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

    public function setup()
    {
        $this->crud->setModel('App\Models\Reservacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reservacion');
        $this->crud->setEntityNameStrings('Reservación', 'Reservaciones');
    }

    protected function setupListOperation()
    {
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
      return [
        1 => 'Normal',
        2 => 'Suite',
        3 => 'Lujo',
      ];
      }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'tipo_habitacion_id', $value);
      });

      $this->crud->addFilter([
        'name'  => 'status',
        'type'  => 'select2',
        'label' => 'Status De La Reservación'
      ], function () {
        return [
          1 => 'Activa',
          2 => 'Inactiva',
        ];
      }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'status_reservacion', $value);
      });

      $this->crud->addFilter([
        'type' => 'text',
        'name' => 'costo_total',
        'label'=> 'Costo Total'
      ], 
      false, 
      function($value) { // if the filter is active
         $this->crud->addClause('where', 'costo_total', '=', $value);
      });

      $this->crud->addFilter([
        'type' => 'text',
        'name' => 'habitacion_id',
        'label'=> 'Habitación'
      ], 
      false, 
      function($value) { // if the filter is active
         $this->crud->addClause('where', 'habitacion_id', '=', $value);
      });

      $this->crud->addFilter([
        'name' => 'cliente_id',
        'type' => 'select2',
        'label'=> 'Clientes'
      ], function() {
          return Cliente::all()->pluck('nombre', 'id')->toArray();
      }, function($value) { // if the filter is active
          $this->crud->addClause('where', 'cliente_id', $value);
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

        // TODO: remove setFromDb() and manually define Fields
        //$this->crud->setFromDb();
        $this->crud->addField([   // date_picker
            'name' => 'fecha_entrada',
            'type' => 'datetime_picker',
            'label' => 'Fecha De Entrada',
            'minDate' => Carbon::now()->toDateString(),
            // optional:
            'date_picker_options' => [
               'todayBtn' => 'linked',
               'format' => 'DD/MM/YYYY HH:mm',
               'language' => 'es'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
         ]);

         $this->crud->addField([   // date_picker
            'name' => 'fecha_salida',
            'type' => 'datetime_picker',
            'label' => 'Fecha De Salida',
            'minDate' => Carbon::now()->toDateString(),
            // optional:
            'date_picker_options' => [
               'todayBtn' => 'linked',
               'format' => 'DD/MM/YYYY HH:mm',
               'language' => 'es'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
         ]);

         $this->crud->addField([   // select_from_array
            'name' => 'status_reservacion',
            'label' => "Status De La Reservación",
            'type' => 'select2_from_array',
            'options' => ['0' => 'Inactiva', '1' => 'Activa'],
            'allows_null' => false,
            'default' => '1',
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Habitación",
            'type' => 'select2',
            'name' => 'habitacion_id', // the db column for the foreign key
            'entity' => 'Habitacion', // the method that defines the relationship in your Model
            'attribute' => 'numero', // foreign key attribute that is shown to user
            'options'   => (function ($query) {
                return $query->where('status_id', '!=', '2')->get();
            }), // force the related options to be a custom query, instead of all();
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
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
                'class' => 'form-group col-md-4'
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
        ]);

        $this->crud->addField([
            'name' => 'costo_total',
            'label' => 'Calcular Total',
            'type' => 'calcular_total',//VISTA PERSONALIZADA, SE ENCUENTRA EN resources/views/vendor/crud/fields
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 

        ]);
        
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store()
    {
        $costo_total = $this->crud->request->request->get('costo_total');

        if($costo_total){
            $this->crud->request->request->set('costo_total', str_replace(',', '', $costo_total));
        }
        // do something before validation, before save, before everything; for example:
        // $this->crud->request->request->add(['author_id'=> backpack_user()->id]);
        // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
        // $this->crud->request->request->remove('password_confirmation');
        // $this->crud->removeField('password_confirmation');
        $costo_total = $this->crud->request->request->get('costo_total');

        if($costo_total){
            $this->crud->request->request->set('costo_total', str_replace(',', '', $costo_total));
        }

        $response = $this->traitStore();
        // do something after save
        $reservacion = Reservacion::find($this->crud->entry->id);

        if($reservacion){
            $cliente = $reservacion->cliente;
            Mail::to($cliente->email)->send(new ReservacionExitosa($reservacion, $cliente, backpack_user()));
        }

        return $response;
    }

    public function calcularTotalFechas(Request $request){
        // dd($request->request); //CON ESTA LINEA VES TODO LO QUE LE HAS MANDADO
        $fecha_entrada = $request->get('fecha_entrada');
        $fecha_salida = $request->get('fecha_salida');
        $habitacion = Habitacion::find($request->get('habitacion'));
        $promocion = Promocion::find($request->get('promocion'));
        $total = 0;

        if($fecha_entrada && $fecha_salida && $habitacion){
            $fecha_entrada = new Carbon($fecha_entrada);
            $fecha_salida = new Carbon($fecha_salida);

            $tipo_habitacion = $habitacion->tipoHabitacion;

            $dias = $fecha_entrada->diffInDays($fecha_salida, false);
            $total = $tipo_habitacion->costo * ($dias === 0 ? 1 : $dias);
    
            if($promocion){
                $total -= (($total / 100) * $promocion->descuento);
            }
        }

        return $total;
    }
}
