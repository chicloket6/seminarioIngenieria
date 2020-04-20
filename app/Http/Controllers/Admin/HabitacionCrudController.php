<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HabitacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HabitacionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HabitacionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Habitacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/habitacion');
        $this->crud->setEntityNameStrings('habitación', 'habitaciones');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        //$this->crud->setFromDb();
        $this->crud->addColumn([
            'name' => 'numero', // The db column name
            'label' => "Número", // Table column heading
         ]);

         $this->crud->addColumn([
            'name' => 'tipoHabitacion.nombre', // The db column name
            'label' => "Tipo de habitación", // Table column heading
         ]);

         $this->crud->addColumn([
            'name' => 'status.nombre', // The db column name
            'label' => "Estatus", // Table column heading
         ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HabitacionRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        //$this->crud->setFromDb();
        $this->crud->addField([
            'name' => 'numero',
            'label' => 'Número',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Tipo de habitación",
            'type' => 'select2',
            'name' => 'tipos_habitacion_id', // the db column for the foreign key
            'entity' => 'tipoHabitacion', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Estatus",
            'type' => 'select2',
            'name' => 'status_id', // the db column for the foreign key
            'entity' => 'status', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
