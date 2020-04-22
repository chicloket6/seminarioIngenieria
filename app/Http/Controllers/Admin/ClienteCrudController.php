<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClienteRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ClienteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClienteCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Cliente');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/cliente');
        $this->crud->setEntityNameStrings('Cliente', 'Clientes');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setFromDb();
        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'nombre',
            'label'=> 'Nombre'
          ], 
          false, 
          function($value) { // if the filter is active
             $this->crud->addClause('where', 'nombre', 'like', '%'.$value.'%');
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'email',
            'label'=> 'Email'
          ], 
          false, 
          function($value) { // if the filter is active
             $this->crud->addClause('where', 'email', 'like', '%'.$value.'%');
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'telefono',
            'label'=> 'Telefono'
          ], 
          false, 
          function($value) { // if the filter is active
             $this->crud->addClause('where', 'telefono', 'like', '%'.$value.'%');
          });
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ClienteRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        //$this->crud->setFromDb();
        $this->crud->addField(
            [   // Text
                'name' => 'nombre',
                'label' => "Nombre",
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                  ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            ]
        );
        $this->crud->addField(
            [   // Text
                'name' => 'email',
                'label' => "Correo",
                'type' => 'email',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                  ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            ]
        );
        $this->crud->addField(
            [   // Text
                'name' => 'telefono',
                'label' => "Telefono",
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                  ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            ]
        );
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
