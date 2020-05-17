<?php

use Illuminate\Database\Seeder;
use App\Models\ServicioAdicional;

class ServiciosAdicionalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sa = new ServicioAdicional();
        $sa->nombre = 'Desayunos incluidos';
        $sa->costo = 700;
        $sa->save();

        $sa = new ServicioAdicional();
        $sa->nombre = 'Spa';
        $sa->costo = 1800;
        $sa->save();

        $sa = new ServicioAdicional();
        $sa->nombre = 'Barra de bebidas 3pm-12pm';
        $sa->costo = 2000;
        $sa->save();

        $sa = new ServicioAdicional();
        $sa->nombre = 'Picina';
        $sa->costo = 250;
        $sa->save();
    }
}
