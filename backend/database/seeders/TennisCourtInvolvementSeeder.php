<?php

namespace Database\Seeders;

use App\Models\TennisCourtInvolvement;
use Illuminate\Database\Seeder;

class TennisCourtInvolvementSeeder extends Seeder
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
                'tennis_court_involvement_table_id'=>rand(1,2),
                'tennis_court_id'=> $i,
                'user_id'=>rand(1,4),
            ];
            $i++;
        }

        TennisCourtInvolvement::insert($nn);
    }
}
