<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HabitacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Validator;

use App\Models\Habitacion;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }


    public function setup()
    {
        $this->crud->setModel('App\Models\Habitacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/habitacion');
        $this->crud->setEntityNameStrings('habitación', 'habitaciones');
    }

    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
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
          'name' => 'max_adultos',
          'label' => 'Max. Adultos'
         ]);

         $this->crud->addColumn([
          'name' => 'max_ninos',
          'label' => 'Max. Niños'
         ]);

         $this->crud->addColumn([
            'name' => 'status.nombre', // The db column name
            'label' => "Estatus", // Table column heading
         ]);


         $this->crud->addFilter([
            'type' => 'text',
            'name' => 'numero',
            'label'=> 'Número'
          ], 
          false, 
          function($value) { // if the filter is active
             $this->crud->addClause('where', 'numero', 'like', '%'.$value.'%');
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
            'name'  => 'statusHabitacion',
            'type'  => 'select2',
            'label' => 'Status'
          ], function () {
            return [
              1 => 'Disponible',
              2 => 'No disponible',
              3 => 'En Remodelación',
            ];
          }, function ($value) { // if the filter is active
                $this->crud->addClause('where', 'status_id', $value);
          });
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
                'class' => 'form-group col-md-6'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([  // Select2
            'label' => "Tipo de habitación",
            'type' => 'select2',
            'name' => 'tipo_habitacion_id', // the db column for the foreign key
            'entity' => 'tipoHabitacion', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
              ], // change the HTML attributes for the field wrapper - mostly for resizing fields 
        ]);

        $this->crud->addField([
          'name' => 'max_adultos',
          'label' => 'Cantidad máxima de adultos (1 - 8)',
          'type' => 'number',
          'attributes' => ['min' => 1, 'max' => 8],
          'wrapperAttributes' => [
            'class' => 'form-group col-md-6'
          ],
        ]);

        $this->crud->addField([
          'name' => 'max_ninos',
          'label' => 'Cantidad máxima de niños (1 - 8)',
          'type' => 'number',
          'attributes' => ['min' => 1, 'max' => 8],
          'wrapperAttributes' => [
            'class' => 'form-group col-md-6'
          ],
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

    public function store()
    {
        if(Habitacion::first()){
            $data = $this->crud->request->all();
            $validator = Validator::make($data, Habitacion::first()->rules());
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput($data);
            }
        }
        // do something before validation, before save, before everything; for example:
        // $this->crud->request->request->add(['author_id'=> backpack_user()->id]);
        // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
        // $this->crud->request->request->remove('password_confirmation');
        // $this->crud->removeField('password_confirmation');
        $response = $this->traitStore();
        // do something after save
        return $response;
    }

    public function update()
    {
        if(Habitacion::first()){
            $data = $this->crud->request->all();
            $validator = Validator::make($data, Habitacion::first()->rules($data['id']));
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput($data);
            }
        }
        
        // do something before validation, before save, before everything; for example:
        // $this->crud->request->request->add(['author_id'=> backpack_user()->id]);
        // $this->crud->addField(['type' => 'hidden', 'name' => 'author_id']);
        // $this->crud->request->request->remove('password_confirmation');
        // $this->crud->removeField('password_confirmation');
        $response = $this->traitUpdate();
        // do something after save
        return $response;
    }
}
