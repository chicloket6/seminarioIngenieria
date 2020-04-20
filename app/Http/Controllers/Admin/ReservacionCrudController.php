<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReservacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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

    public function setup()
    {
        $this->crud->setModel('App\Models\Reservacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reservacion');
        $this->crud->setEntityNameStrings('Reservación', 'Reservaciones');

        //$this->crud->addClause('whereHas', 'habitacion', function($query){
          //  $query->where('status_id','!=','1');
        //});
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        //$this->crud->setFromDb();
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
            'options' => [0 => 'Inactiva', 1 => 'Activa']
        ]);

        $this->crud->addColumn([
            'name' => 'Habitacion.numero', // The db column name
            'label' => "Habitación", // Table column heading
         ]);

         $this->crud->addColumn([
            'name' => 'Cliente.nombre', // The db column name
            'label' => "Cliente", // Table column heading
         ]);
         $this->crud->addColumn([
            'name' => 'MetodoPago.nombre', // The db column name
            'label' => "Métodos De Pago", // Table column heading
         ]);
         $this->crud->addColumn([
            'name' => 'Promocion.nombre', // The db column name
            'label' => "Promociones", // Table column heading
         ]);
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
            // optional:
            'date_picker_options' => [
               'todayBtn' => 'linked',
               'format' => 'dd-mm-yyyy',
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
            // optional:
            'date_picker_options' => [
               'todayBtn' => 'linked',
               'format' => 'dd-mm-yyyy',
               'language' => 'es'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
         ]);

         $this->crud->addField([   // select_from_array
            'name' => 'status_reservacion',
            'label' => "Status De La Reservación",
            'type' => 'select_from_array',
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
        
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
