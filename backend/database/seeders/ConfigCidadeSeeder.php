<?php

namespace Database\Seeders;

use App\Models\ConfigCidade;
use Illuminate\Database\Seeder;

class ConfigCidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* $path = database_path('seeders'.DIRECTORY_SEPARATOR.'config-estado.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql); */

        ConfigCidade::insert([
            [
                'name' => 'Três Pontas',
                'user_id' => 1,
                'config_estados_id' => 11,
                'created_at' => now(),
            ],[
                'name' => 'Varginha',
                'user_id' => 1,
                'config_estados_id' => 11,
                'created_at' => now(),
            ],[
                'name' => 'Santana da Vargem',
                'user_id' => 1,
                'config_estados_id' => 11,
                'created_at' => now(),
            ],
        ]);
    }
}

/* This is how its gonna work:

add namespace Seeder\DatabaseTester; to your seeder

add use Illuminate\Support\Facades\Artisan; at the top of your test

use to run Artisan::call('db:seed', ['--class' => 'Seeder\DatabaseTester\Users']);

This one will work for any folder, just don't forget to add Seeder\ before your path to seeder. */
