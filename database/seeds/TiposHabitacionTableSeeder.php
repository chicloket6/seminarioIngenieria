<?php

use Illuminate\Database\Seeder;
use App\Models\TipoHabitacion;

class TiposHabitacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tipo = new TipoHabitacion();
        $tipo->nombre = 'Normal';
        $tipo->costo = 300;
        $tipo->save();

        $tipo = new TipoHabitacion();
        $tipo->nombre = 'Suite';
        $tipo->costo = 700;
        $tipo->save();

        $tipo = new TipoHabitacion();
        $tipo->nombre = 'Lujo';
        $tipo->costo = 1000;
        $tipo->save();
    }
}
