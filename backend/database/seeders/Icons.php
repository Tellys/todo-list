<?php

namespace Database\Seeders;

use App\Models\Icons as ModelsIcons;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class Icons extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelsIcons::truncate();
        //$json = Storage::disk('local')->get('database/data/icons.json');
        $json = json_decode(file_get_contents(database_path('data/icons.json')), true);

        foreach ($json['icons'] as $value) {
            ModelsIcons::create([
                "name" => $value,
            ]);
        }
    }
}
