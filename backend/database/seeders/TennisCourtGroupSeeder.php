<?php

namespace Database\Seeders;

use App\Models\Slug;
use App\Models\TennisCourtGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class TennisCourtGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array1 = [
            [
                'id' => 1,
                'name' => 'Areia',
                'tennis_court_type_id' => rand(1,3),
                'tennis_court_description_table_id' => '1,2,3,4,5,6,7,8,9,10',
                'description' => fake()->text(100),
                'user_id' => 1,
                'created_at' => now(),
                //'slug' => 'casa'
            ],
            [
                'id' => 2,
                'name' => 'Saibro ',
                'tennis_court_type_id' => rand(1,3),
                'tennis_court_description_table_id' => '2',
                'description' => fake()->text(100),
                'user_id' => 1,
                'created_at' => now(),
                //'slug' => 'terrenos'
            ],
            [
                'id' => 3,
                'name' => 'Asfalto',
                'tennis_court_type_id' => rand(1,3),
                'tennis_court_description_table_id' => '1,2,3,4,5,6,7,8,9,10',
                'description' => fake()->text(100),
                'user_id' => 1,
                'created_at' => now(),
                //'slug' => 'apartamentos'
            ],
            [
                'id' => 4,
                'name' => 'Grama Natural',
                'tennis_court_type_id' => rand(1,3),
                'tennis_court_description_table_id' => '1,2,3,4,5,6,7,8,9,10',
                'description' => fake()->text(100),
                'user_id' => 1,
                'created_at' => now(),
                //'slug' => 'TennisCourt-rurais'
            ],
            [
                'id' => 5,
                'name' => 'Grama Sintética',
                'tennis_court_type_id' => rand(1,3),
                'tennis_court_description_table_id' => '1,2',
                'description' => fake()->text(100),
                'user_id' => 1,
                'created_at' => now(),
                //'slug' => 'sitios'
            ],
            [
                'id' => 6,
                'name' => 'Carpete',
                'tennis_court_type_id' => rand(1,3),
                'tennis_court_description_table_id' => '1,2',
                'description' => fake()->text(100),
                'user_id' => 1,
                'created_at' => now(),
                //'slug' => 'fazenda'
            ],
        ];
        TennisCourtGroup::insert($array1);

        $array2 = [
            [
                'name' => 'tennis-court-group/5',
                'redirect' => Str::slug($array1[0]['name']),
                'type' => 301,
                'module' => 'App\Http\Controllers\TennisCourtGroupController',
                'module_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'tennis-court-group/9',
                'redirect' => Str::slug($array1[1]['name']),
                'type' => 301,
                'module' => 'App\Http\Controllers\TennisCourtGroupController',
                'module_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'tennis-court-group/14',
                'redirect' => Str::slug($array1[2]['name']),
                'type' => 301,
                'module' => 'App\Http\Controllers\TennisCourtGroupController',
                'module_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'tennis-court-group/17',
                'redirect' => Str::slug($array1[3]['name']),
                'type' => 301,
                'module' => 'App\Http\Controllers\TennisCourtGroupController',
                'module_id' => 4,
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'tennis-court-group/18',
                'redirect' => Str::slug($array1[4]['name']),
                'type' => 301,
                'module' => 'App\Http\Controllers\TennisCourtGroupController',
                'module_id' => 5,
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'tennis-court-group/19',
                'redirect' => Str::slug($array1[5]['name']),
                'type' => 301,
                'module' => 'App\Http\Controllers\TennisCourtGroupController',
                'module_id' => 6,
                'user_id' => 1,
                'created_at' => now(),
            ],
        ];
        Slug::insert($array2);
    }
}
