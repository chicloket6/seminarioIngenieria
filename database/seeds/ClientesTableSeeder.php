<?php

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $cliente = new Cliente();
        // $cliente->nombre = 'Erick Raúl Márquez Gutiérrez';
        // $cliente->email = 'samarripa_17@hotmail.com';
        // $cliente->telefono = '3315654989';
        // $cliente->save();

        // $cliente = new Cliente();
        // $cliente->nombre = 'Israel Martinez Jimenez';
        // $cliente->email = 'raya9631@gmail.com';
        // $cliente->telefono = '3345455454';
        // $cliente->save();

        // $cliente = new Cliente();
        // $cliente->nombre = 'Erick Raúl Márquez Gutiérrez';
        // $cliente->email = 'diego@mail.com';
        // $cliente->telefono = '3389898989';
        // $cliente->save();

        App\Models\Cliente::create([
            'nombre' => 'Erick Raúl Márquez Gutiérrez',
            'email' => 'samarripa_17@hotmail.com',
            'telefono' => '3315654989',
        ]);

        App\Models\Cliente::create([
            'nombre' => 'Israel Martínez Jiménez',
            'email' => 'raya9631@gmail.com',
            'telefono' => '3345455454',
        ]);

        App\Models\Cliente::create([
            'nombre' => 'Erick Raúl Márquez Gutiérrez',
            'email' => 'diego@mail.com',
            'telefono' => '3389898989',
        ]);

        factory(App\Models\Cliente::class, 97)->create();
    }
}
