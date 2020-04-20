<?php

use Illuminate\Database\Seeder;
use App\Models\Promocion;

class PromocionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $promocion = new Promocion();
        $promocion->nombre = '20% en suites';
        $promocion->fecha_inicio = '2020-04-20 00:00:00';
        $promocion->fecha_final = '2020-05-20 23:59:59';
        $promocion->descuento = 20;
        $promocion->save();

        $promocion = new Promocion();
        $promocion->nombre = '15% en habitaciones de lujo';
        $promocion->fecha_inicio = '2020-04-20 00:00:00';
        $promocion->fecha_final = '2020-05-20 23:59:59';
        $promocion->descuento = 15;
        $promocion->save();

        $promocion = new Promocion();
        $promocion->nombre = '10% en habitaciones normales';
        $promocion->fecha_inicio = '2020-04-20 00:00:00';
        $promocion->fecha_final = '2020-05-20 23:59:59';
        $promocion->descuento = 10;
        $promocion->save();
    }
}
