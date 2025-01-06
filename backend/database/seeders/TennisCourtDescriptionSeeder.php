<?php

namespace Database\Seeders;

use App\Models\TennisCourtDescription;
use Illuminate\Database\Seeder;

class TennisCourtDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nItems = 50*16;
        $rand1 = rand(1,16);
        $rand2 = rand(1,50); 
        $i = 1;
        $nn = [];
        while ($i <= $nItems) {

            $nn[] = 
            [
                'tennis_court_description_table_id'=>$rand1 = $this->preventRand($rand1, 1,16),
                'tennis_court_id'=> $rand2 = $this->preventRand($rand2, 1,50),
                'value'=>rand(1,10),
                'user_id'=>rand(1,4),
                'created_at'=>now(),
            ];
            $i++;
        }

        TennisCourtDescription::insert($nn);

    }

    private function preventRand($a = null, $min, $max){
        $rand1 = rand($min, $max);

        if ($a == $rand1) {
            return $this->preventRand($a, $min, $max);
        }

        return $rand1;
    }
}
