<?php

namespace Database\Seeders;

use App\Models\TennisCourtType;
use Illuminate\Database\Seeder;

class TennisCourtTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TennisCourtType::insert([
            [
                'name' => 'Coberta (Indoor)',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Descoberta (OutDoor)',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Outro',
                'user_id' => 1,
                'created_at' => now(),
            ],

        ]);
    }
}
