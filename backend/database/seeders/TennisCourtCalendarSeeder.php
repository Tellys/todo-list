<?php

namespace Database\Seeders;

use App\Models\TennisCourtCalendar;
use Illuminate\Database\Seeder;

class TennisCourtCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $day = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $nItems = 50;
        $i = 1;
        $nn = [];

        //$statusArray = ['reserved', 'confirmed', 'canceled']; $this->data['campos']['status']['html']['options']
        $statusArray = (new TennisCourtCalendar())->campos['status']['html']['options'];

        //default importante para relacionamentos CART funcionar
        $randDay = rand(0, 5);
        $randHour = rand(8, 20);

        $date = date("Y-m-d");
        $timeStart = date('Y-m-d H:i:s', strtotime('+' . $randDay . ' days +' . $randHour . ' hours', strtotime($date)));
        $timeEnd = date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($timeStart)));

        $status = array_rand($statusArray);

        $nn[] = [
            'tennis_court_id' => 1,
            'user_id' => 1,
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
            'status' => $statusArray[$status],
            'created_at' => now(),
            'tennis_court_opening_hour_id' => $this->retunIdTennisCourtOpeningHourId((int) $i, date('l', strtotime($timeStart))),
        ];

        while ($i <= $nItems) {

            //seed test
            $j = 0;
            while ($j <= rand(0, count($day))) {
                $randDay = rand(0, 5);
                $randHour = rand(8, 20);

                $date = date("Y-m-d");
                $timeStart = date('Y-m-d H:i:s', strtotime('+' . $randDay . ' days +' . $randHour . ' hours', strtotime($date)));
                $timeEnd = date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($timeStart)));

                $status = array_rand($statusArray);

                $nn[] = [
                    'tennis_court_id' => (int) $i,
                    'user_id' => (int) rand(1, 4),
                    'time_start' => $timeStart,
                    'time_end' => $timeEnd,
                    'status' => $statusArray[$status],
                    'created_at' => now(),
                    'tennis_court_opening_hour_id' => $this->retunIdTennisCourtOpeningHourId((int) $i, date('l', strtotime($timeStart))),
                ];

                $j++;
            }
            $i++;
        }
        ///
        TennisCourtCalendar::insert($nn);
    }

    function retunIdTennisCourtOpeningHourId($tennisCourtId, $day)
    {
        $tennisCourtOpeningHour = new \App\Http\Controllers\Api\TennisCourtOpeningHourController();

        $r = $tennisCourtOpeningHour->Model
            ->where('tennis_court_id', $tennisCourtId)
            ->where('day', $day)
            ->first();

        return $r->id;
    }
}
