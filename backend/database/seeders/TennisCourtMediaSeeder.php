<?php

namespace Database\Seeders;

use App\Models\TennisCourtMedia;
use Illuminate\Database\Seeder;

class TennisCourtMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nItems = 50;
        $i = 1;
        $nn = [];
        while ($i <= $nItems) {
            $nn[] = [
                'name' => fake()->name(),
                'description' => fake()->text(),
                'tennis_court_id'=> rand(1,50),
                'user_id'=>rand(1,4),
            ];
            $i++;
        }

        TennisCourtMedia::insert($nn);
    }
}
