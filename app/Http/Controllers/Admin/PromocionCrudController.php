<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Requests\PromocionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Promocion;

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
            'type'  => 'date',
            'name'  => 'fecha_inicio',
            'label' => 'Fecha De Inicio',
          ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                 $this->crud->addClause('whereDate', 'fecha_inicio', $value);
            });
            $this->crud->addFilter([
                'type'  => 'date',
                'name'  => 'fecha_final',
                'label' => 'Fecha De Fin',
              ],
                false,
                function ($value) { // if the filter is active, apply these constraints
                     $this->crud->addClause('whereDate', 'fecha_final', $value);
                });
                $this->crud->addFilter([
                    'type' => 'text',
                    'name' => 'descuento',
                    'label'=> 'Descuento',
                  ], 
                  false, 
                  function($value) { // if the filter is active
                     $this->crud->addClause('where', 'descuento', '=', $value);
                  });
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
            ]
        );
        $this->crud->addField(
            [   // Text
                'name' => 'fecha_final',
                'label' => "Fecha De Fin",
                'type' => 'datetime_picker',
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

    public function promocionesVigentes(){
        return Promocion::whereDate('fecha_inicio', '>=', Carbon::now())->get();
    }
}
