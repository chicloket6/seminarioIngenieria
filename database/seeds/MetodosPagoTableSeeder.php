<?php

use Illuminate\Database\Seeder;
use App\Models\MetodoPago;

class MetodosPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $metodos = array('Tarjeta', 'Efectivo', 'Paypal');

        foreach($metodos as $m){
            $metodo = new MetodoPago();
            $metodo->nombre = $m;
            $metodo->save();
        }
    }
}
