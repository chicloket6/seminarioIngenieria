<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Erick';
        $user->email = 'erick@mail.com';
        $user->password = bcrypt('123456');
        $user->save();

        $user = new User();
        $user->name = 'Israel';
        $user->email = 'israel@mail.com';
        $user->password = bcrypt('123456');
        $user->save();

        $user = new User();
        $user->name = 'Diego';
        $user->email = 'diego@mail.com';
        $user->password = bcrypt('123456');
        $user->save();
    }
}
