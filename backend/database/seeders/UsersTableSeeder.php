<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = ['Três pontas', 'Varginha', 'Santana da Vargem', 'Boa Esperança', 'Três Corações'];

        if (!User::find(1)) {
            User::firstOrCreate([
                'email'     => 'diretor@mail.com',
                'name'      => 'User Diretor Test',
                'password'  => bcrypt('diretor'), //admin
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'users_level_id' => 1, //diretor
                'user_id' => 1,
                'cpf'=> '35484709040',

                'city' => $cities[array_rand($cities)], // fake()->city(),
                'state' => 'Minas Gerais', //fake()->stateAbbr(),
                'state_code' => 'MG',
                'country' => 'Brasil',
                'country_code' => 'BR',
                'zip_code' => '37' . fake()->randomNumber(6),
                'birthday' => fake()->date(),
                'phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'cell_phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'description' => fake()->text(),
                'address' => fake()->streetName(),
                'address_neighborhood' => fake()->firstName(),
                'address_num' => fake()->randomNumber(4),
                'lat' => $lat = -21.3796535,
                'lng' => $lng = -45.5157768,
                //'geo_point' => DB::raw("(ST_GeomFromText('POINT(" . $lat . " " .  $lng . ")'))"),

            ]);

            User::firstOrCreate([
                'email'     => 'admin@mail.com',
                'name'      => 'User Admin Test',
                'password'  => bcrypt('admin'), //admin
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'users_level_id' => 2, //administrador
                'user_id' => 2,

                'city' => $cities[array_rand($cities)], // fake()->city(),
                'state' => 'Minas Gerais', //fake()->stateAbbr(),
                'state_code' => 'MG',
                'country' => 'Brasil',
                'country_code' => 'BR',
                'zip_code' => '37' . fake()->randomNumber(6),
                'birthday' => fake()->date(),
                'phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'cell_phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'description' => fake()->text(),
                'address' => fake()->streetName(),
                'address_neighborhood' => fake()->firstName(),
                'address_num' => fake()->randomNumber(4),
                'lat' => $lat = -20.3 . fake()->randomNumber(6),
                'lng' => $lng = -45.5 . fake()->randomNumber(6),
                //'geo_point' => DB::raw("(ST_GeomFromText('POINT(" . $lat . " " .  $lng . ")'))"),
            ]);

            User::firstOrCreate([
                'email'     => 'editor@mail.com',
                'name'      => 'User Editor Test',
                'password'  => bcrypt('editor'), //admin
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'users_level_id' => 3, //editor
                'user_id' => 3,

                'city' => $cities[array_rand($cities)], // fake()->city(),
                'state' => 'Minas Gerais', //fake()->stateAbbr(),
                'state_code' => 'MG',
                'country' => 'Brasil',
                'country_code' => 'BR',
                'zip_code' => '37' . fake()->randomNumber(6),
                'birthday' => fake()->date(),
                'phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'cell_phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'description' => fake()->text(),
                'address' => fake()->streetName(),
                'address_neighborhood' => fake()->firstName(),
                'address_num' => fake()->randomNumber(4),
                'lat' => $lat = -22.3 . fake()->randomNumber(6),
                'lng' => $lng = -45.5 . fake()->randomNumber(6),

                //'geo_point' => DB::raw("(ST_GeomFromText('POINT(" . $lat . " " .  $lng . ")'))"),
            ]);

            User::firstOrCreate([
                'email'     => 'cliente@mail.com',
                'name'      => 'User Cliente Test',
                'password'  => bcrypt('cliente'), 
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'users_level_id' => 4, //cliente
                'user_id' => 4,

                'city' => $cities[array_rand($cities)], // fake()->city(),
                'state' => 'Minas Gerais', //fake()->stateAbbr(),
                'state_code' => 'MG',
                'country' => 'Brasil',
                'country_code' => 'BR',
                'zip_code' => '37' . fake()->randomNumber(6),
                'birthday' => fake()->date(),
                'phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'cell_phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'description' => fake()->text(),
                'address' => fake()->streetName(),
                'address_neighborhood' => fake()->firstName(),
                'address_num' => fake()->randomNumber(4),
                'lat' => $lat = -22.3 . fake()->randomNumber(6),
                'lng' => $lng = -45.5 . fake()->randomNumber(6),

                //'geo_point' => DB::raw("(ST_GeomFromText('POINT(" . $lat . " " .  $lng . ")'))"),
            ]);
        }

        $nItems = 50;
        $i = 1;
        $nn = [];
        while ($i <= $nItems) {
            $nn[] = [
                'email'     => fake()->unique()->safeEmail(),
                'name'      => $name = fake()->name(),
                'name_corporate' => 'Razão Social - ' . $name,
                'password'  => bcrypt('cliente'), //admin
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'users_level_id' => 4, //administrador
                'user_id' => 1,
                'created_at' => now(),

                'city' => $cities[array_rand($cities)], // fake()->city(),
                'state' => 'Minas Gerais', //fake()->stateAbbr(),
                'state_code' => 'MG',
                'country' => 'Brasil',
                'country_code' => 'BR',
                'zip_code' => '37' . fake()->randomNumber(6),
                'birthday' => fake()->date(),
                'phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'cell_phone' => fake()->randomNumber(2) . fake()->randomNumber(9),
                'description' => fake()->text(),
                'address' => fake()->streetName(),
                'address_neighborhood' => fake()->firstName(),
                'address_num' => fake()->randomNumber(4),
                'lat' => $lat = -rand(21, 23).'.'.fake()->randomNumber(7),
                'lng' => $lng = -rand(44, 45).'.'.fake()->randomNumber(7),

                //'geo_point' => DB::raw("(ST_GeomFromText('POINT(" . $lat . " " .  $lng . ")'))"),
            ];
            $i++;
        }

        User::insert($nn);
    }
}
