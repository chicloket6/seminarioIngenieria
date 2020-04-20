<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PromocionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PromocionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PromocionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Promocion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/promocion');
        $this->crud->setEntityNameStrings('Promoción', 'Promociones');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->setFromDb();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PromocionRequest::class);

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
                'name' => 'fecha_inicio',
                'label' => "Fecha De Inicio",
                'type' => 'datetime_picker',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                  ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            ]
        );
        $this->crud->addField(
            [   // Text
                'name' => 'fecha_final',
                'label' => "Fecha De Fin",
                'type' => 'datetime_picker',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                  ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
            ]
        );
        $this->crud->addField(
            [   // Text
                'name' => 'descuento',
                'label' => "Descuento %",
                'type' => 'number',
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
