<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesUsuarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'role_id' => 1,
                'model_type' => 'App\Models\BackpackUser',
                'model_id' => 1,
            ],
            [
                'role_id' => 2,
                'model_type' => 'App\Models\BackpackUser',
                'model_id' => 2,
            ],
            [
                'role_id' => 2,
                'model_type' => 'App\Models\BackpackUser',
                'model_id' => 3,
            ]
        ];

        DB::table('model_has_roles')->insert($roles);
    }
}
