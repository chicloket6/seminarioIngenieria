<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReporteRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Excel;

use App\Exports\Reporte;
use App\Models\Reservacion;
use App\Mail\ReservacionExitosa;
use App\Models\Habitacion;
use App\Models\Promocion;
use \App\Models\Cliente;
use \App\Models\MetodoPago; 
use \App\Models\TipoHabitacion; 

/**
 * Class ReporteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReporteCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Reservacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reporte');
        $this->crud->setEntityNameStrings('reporte', 'reportes');
    }

    protected function setupListOperation()
    {
        $this->crud->removeAllButtons();

        if(backpack_user()->hasRole('Gerente')){
            $this->crud->addButtonFromModelFunction('top', 'descargarExcel', 'descargarExcelButton', 'end');
        }
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        // $this->crud->setFromDb();
        
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
            return TipoHabitacion::all()->pluck('nombre', 'id')->toArray();
          }, function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'habitacion.tipoHabitacion', function($q) use ($value){
                    $q->where('id', $value);
                });
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
            'name' => 'habitacion_id',
            'label'=> 'Habitación'
          ], 
          false, 
          function($value) { // if the filter is active
             $this->crud->addClause('where', 'habitacion_id', 'like', '%'.$value.'%');
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
        $this->crud->setValidation(ReporteRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        $this->crud->setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function descargarReporte(){
        return Excel::download(new Reporte(), 'reporte.xlsx');
    }
}
