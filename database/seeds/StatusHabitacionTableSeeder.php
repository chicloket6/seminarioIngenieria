<?php

use Illuminate\Database\Seeder;
use App\Models\StatusHabitacion;

class StatusHabitacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $st = array('Disponible', 'No disponible', 'En remodelación');

        foreach($st as $s){
            $status = new StatusHabitacion();
            $status->nombre = $s;
            $status->save();
        }
    }
}
