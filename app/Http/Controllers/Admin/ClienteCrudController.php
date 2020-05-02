<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClienteRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

use App\Models\ImagenPerfil;

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

    public function cambiarImagenPerfil(Request $request){
        if($request->has('imagen')){
            $file = $request->file('imagen');
            $nombre = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $disk = 'publicSystem';

            $ruta = $file->storeAs('imagenesPerfil', $nombre, $disk);

            if(backpack_user()->imagenPerfil){
                backpack_user()->imagenPerfil->ruta = $ruta;
                backpack_user()->imagenPerfil->save();
            }
            else{
                $imagen = new ImagenPerfil();
                $imagen->ruta = $ruta;
                $imagen->user_id = backpack_user()->id;
                $imagen->save();
            }
            
            return redirect()->back();
        }
        return redirect()->back()->withErrors(['Algo salió mal y no se pudo cambiar la imagen de perfil, inténtelo nuevamente']);
    }
}
