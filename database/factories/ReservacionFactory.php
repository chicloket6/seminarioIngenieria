<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reservacion;
use Faker\Generator as Faker;

$factory->define(Reservacion::class, function (Faker $faker) {
    return [
        'fecha_entrada' => $faker->date,
        'fecha_salida' => $faker->date,
        'status_reservacion' => 1,
        'costo_total' =>  $faker -> numberBetween(400, 10000),
        'habitacion_id' => $faker -> numberBetween(1, 100),
        'cliente_id' => $faker -> numberBetween(1, 100),
        'metodo_pago_id' => $faker -> numberBetween(1, 3),
        'promocion_id' => $faker -> numberBetween(1, 3),
    ];
});
