<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(MetodosPagoTableSeeder::class);
        $this->call(TiposHabitacionTableSeeder::class);
        $this->call(StatusHabitacionTableSeeder::class);
        $this->call(HabitacionesTableSeeder::class);
        $this->call(ClientesTableSeeder::class);
        $this->call(PromocionesTableSeeder::class);
    }
}
