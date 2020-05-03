<?php

use Illuminate\Database\Seeder;

class ReservacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Reservacion::class, 100)->create();
    }
}
