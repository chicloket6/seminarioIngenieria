<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServicioAdicionalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ServicioAdicionalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ServicioAdicionalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\ServicioAdicional');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/servicioadicional');
        $this->crud->setEntityNameStrings('Servicio Adicional', 'Servicios Adicionales');
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
             $this->crud->addClause('where', 'nombre', '=', $value);
          });
          $this->crud->addFilter([
            'type' => 'text',
            'name' => 'costo',
            'label'=> 'Costo'
          ], 
          false, 
          function($value) { // if the filter is active
             $this->crud->addClause('where', 'costo', '=', $value);
          });
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ServicioAdicionalRequest::class);

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
                'name' => 'costo',
                'label' => "Costo",
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
