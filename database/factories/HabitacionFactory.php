<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Habitacion;
use Faker\Generator as Faker;

$factory->define(Habitacion::class, function (Faker $faker) {
    static $number = 1;
    return [
        'numero' => $number++,
        'tipo_habitacion_id' => $faker->numberBetween(1, 3),
        'status_id' => $faker->numberBetween(1, 3),
        'max_adultos' => $faker->numberBetween(1, 4),
        'max_ninos' => $faker->numberBetween(1, 8),
    ];
});
