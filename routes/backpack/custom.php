<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    //CRUD (VISTAS)
    Route::crud('metodopago', 'MetodoPagoCrudController');
    Route::crud('habitacion', 'HabitacionCrudController');
    Route::crud('tipohabitacion', 'TipoHabitacionCrudController');
    Route::crud('statushabitacion', 'StatusHabitacionCrudController');
    Route::crud('cliente', 'ClienteCrudController');
    Route::crud('promocion', 'PromocionCrudController');
    Route::crud('reservacion', 'ReservacionCrudController');
    Route::crud('servicioadicional', 'ServicioAdicionalCrudController');
    
    //GETS
    
    //POSTS
    Route::post('Reservacion/calcularTotalFechas', 'ReservacionCrudController@calcularTotalFechas');
}); // this should be the absolute last line of this file