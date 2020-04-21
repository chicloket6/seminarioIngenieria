<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReporteRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Excel;

use App\Exports\Reporte;
use App\Models\Reservacion;

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
        $this->crud->addButtonFromModelFunction('top', 'descargarExcel', 'descargarExcelButton', 'end');
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        // $this->crud->setFromDb();
        $this->crud->addColumn([
            'name' => 'habitacion.numero',
            'label' => '# Habitación'
        ]);

        $this->crud->addColumn([
            'name' => "fecha_entrada", // The db column name
            'label' => "Fecha de entrada", // Table column heading
            'type' => "datetime",
            // 'format' => 'dd/mm/yy H:i:s', // use something else than the base.default_datetime_format config value
        ]);

        $this->crud->addColumn([
            'name' => "fecha_salida", // The db column name
            'label' => "Fecha de salida", // Table column heading
            'type' => "datetime",
             // 'format' => 'l j F Y H:i:s', // use something else than the base.default_datetime_format config value
        ]);
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
