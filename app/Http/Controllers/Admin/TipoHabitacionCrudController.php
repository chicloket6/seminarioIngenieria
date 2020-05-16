<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TipoHabitacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Validator;

use App\Models\TipoHabitacion;

/**
 * Class TipoHabitacionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TipoHabitacionCrudController extends CrudController
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
        $this->crud->setModel('App\Models\TipoHabitacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tipohabitacion');
        $this->crud->setEntityNameStrings('Tipo De Habitación', 'Tipo De Habitación');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        $this->crud->removeButton('show');
        $this->crud->setFromDb();
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(TipoHabitacionRequest::class);

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

    public function store()
    {
        if(TipoHabitacion::first()){
            $data = $this->crud->request->all();
            $validator = Validator::make($data, TipoHabitacion::first()->rules());
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
        if(TipoHabitacion::first()){
            $data = $this->crud->request->all();
            $validator = Validator::make($data, TipoHabitacion::first()->rules($data['id']));
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
