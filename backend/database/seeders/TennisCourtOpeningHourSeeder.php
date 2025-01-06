<?php

namespace Database\Seeders;

use App\Models\TennisCourtOpeningHour;
use Illuminate\Database\Seeder;

class TennisCourtOpeningHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$day = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
        $day = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $nn = [];
        $dItems = 50;
        $d = 1;
        while ($d <= $dItems) {

            foreach ($day as $k => $v) {
                $nn[] = [
                    //'day' => date('l', $start_time),
                    'day' => $v,
                    'hour_start' => 8,
                    'hour_end' => 23,
                    'tennis_court_id' => $d,
                    'user_id' => 1,
                    'created_at' => now(),
                    //'price'=> rand(50, 100)
                    'price'=> 0.01
                ];
            }
            $d++;
        }

        TennisCourtOpeningHour::insert($nn);
    }
}
