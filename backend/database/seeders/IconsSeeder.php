<?php

namespace Database\Seeders;

use App\Models\Icons;
use Illuminate\Database\Seeder;

class IconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Icons::truncate();
        //$json = Storage::disk('local')->get('database/data/icons.json');
        $json = json_decode(file_get_contents(database_path('data/icons.json')), true);

        $array = [];
        $now = now();

        foreach ($json['icons'] as $value) {
            array_push($array, ["name" => $value, 'created_at'=> $now]);
            /* Icons::create([
                "name" => $value,
            ]); */
        }

        Icons::insert($array);
    }
}
