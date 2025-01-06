<?php

namespace Database\Seeders;

use App\Models\UsersLevel;
use Illuminate\Database\Seeder;

class UsersLevelTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsersLevel::insert(
            [
                [
                    'name' => 'Diretor',
                    'created_at' => now()
                ],
                [
                    'name' => 'Administrador',
                    'created_at' => now()
                ],
                [
                    'name' => 'Editor',
                    'created_at' => now()
                ],
                [
                    'name' => 'Cliente',
                    'created_at' => now()
                ],
            ]
        );
    }
}
