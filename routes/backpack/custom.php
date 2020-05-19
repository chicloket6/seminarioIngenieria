<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.


Route::group([//RUTAS PARA GERENCIA SOLAMENTE
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'role:Gerente'],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    //CRUD (VISTAS)
    Route::crud('metodopago', 'MetodoPagoCrudController');
    Route::crud('habitacion', 'HabitacionCrudController');
    Route::crud('tipohabitacion', 'TipoHabitacionCrudController');
    Route::crud('statushabitacion', 'StatusHabitacionCrudController');
    Route::crud('cliente', 'ClienteCrudController');
    Route::crud('promocion', 'PromocionCrudController');
    Route::crud('servicioadicional', 'ServicioAdicionalCrudController');
    
    //GETS
    Route::get('reporte/descargar', 'ReporteCrudController@descargarReporte');
    
    //POSTS
    Route::crud('reporte', 'ReporteCrudController');

});

Route::group([//RUTAS PARA TODOS LOS ROLES (GERENCIA Y RECEPCION)
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'role:Gerente|Recepción'],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    //CRUD (VISTAS)
    Route::crud('cliente', 'ClienteCrudController');
    Route::crud('reservacion', 'ReservacionCrudController');
    
    //GETS
    Route::get('/register', function(){
        return redirect('admin');
    })->name('backpack.auth.register');
    //POSTS
    Route::post('reservacion/calcularTotalFechas', 'ReservacionCrudController@calcularTotalFechas');
    Route::post('reservacion/habitacionesDisponibles', 'ReservacionCrudController@habitacionesDisponibles');
    Route::post('reservacion/obtenerDisponibilidadHabitaciones', 'ReservacionCrudController@obtenerDisponibilidadHabitaciones');
    Route::post('cambiar-imagen-perfil', 'ClienteCrudController@cambiarImagenPerfil');
}); // this should be the absolute last line of this file