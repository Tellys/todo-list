<?php

namespace Database\Seeders;

use App\Models\TennisCourt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TennisCourtSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registration_phases = (new TennisCourt)->registration_phases;

        $cities = ['Três pontas', 'Varginha', 'Santana da Vargem', 'Boa Esperança', 'Três Corações'];

        $nItems = 50;
        $i = 1;
        $nn = [];
        while ($i <= $nItems) {
            $nn[] = [
                'users_level_id' => 1,
                'name' => fake()->name(),
                'city' => $cities[array_rand($cities)], // fake()->city(),
                'state' => 'Minas Gerais', //fake()->stateAbbr(),
                'state_code' => 'MG', 
                'country' => 'Brasil', 
                'country_code' => 'BR', 
                'zip_code' => '37'.fake()->randomNumber(6),
                'birthday' => fake()->date(),
                'phone' => fake()->randomNumber(2).fake()->randomNumber(9),
                'cell_phone' => fake()->randomNumber(2).fake()->randomNumber(9),
                'description' => fake()->text(),
                'address' => fake()->streetName(),
                'address_neighborhood' => fake()->firstName(),
                'address_num' => fake()->randomNumber(4),
                'email' => fake()->unique()->safeEmail(),
                'user_id' => 1,
                'created_at' => now(),
                'lat' => $lat = -rand(21, 23).'.'.fake()->randomNumber(7),
                'lng' => $lng = -rand(44, 45).'.'.fake()->randomNumber(7),
                //'geo_point'=> DB::raw("(ST_GeomFromText('POINT(".$lat ." ".  $lng.")'))"),
                'tennis_court_group_id'=> rand(1,6), 
                'registration_phase'=> $registration_phases[array_rand($registration_phases)]
            ];
            $i++;
        }

        TennisCourt::insert($nn);
    }
}
