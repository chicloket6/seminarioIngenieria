<?php

use Illuminate\Database\Seeder;
use App\Models\Habitacion;

class HabitacionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $habitacion = new Habitacion();
        $habitacion->numero = 1;
        $habitacion->tipo_habitacion_id = 1;
        $habitacion->status_id = 1;
        $habitacion->save();

        $habitacion = new Habitacion();
        $habitacion->numero = 2;
        $habitacion->tipo_habitacion_id = 2;
        $habitacion->status_id = 1;
        $habitacion->save();

        $habitacion = new Habitacion();
        $habitacion->numero = 3;
        $habitacion->tipo_habitacion_id = 3;
        $habitacion->status_id = 1;
        $habitacion->save();
    }
}
