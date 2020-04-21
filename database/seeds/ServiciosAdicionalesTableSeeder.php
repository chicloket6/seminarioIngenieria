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
        $sa->nombre = 'Vino tinto';
        $sa->costo = 700;
        $sa->save();

        $sa = new ServicioAdicional();
        $sa->nombre = 'Pastel de vainilla';
        $sa->costo = 1800;
        $sa->save();

        $sa = new ServicioAdicional();
        $sa->nombre = 'Pizza de pepperoni';
        $sa->costo = 250;
        $sa->save();
    }
}
