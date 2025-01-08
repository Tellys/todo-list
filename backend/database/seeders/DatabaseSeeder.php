<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\TennisCourtCalendar;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersLevelTable::class);
        $this->call(UsersTableSeeder::class);

        $this->call(IconsSeeder::class);
        
        $this->call(ConfigSistemSeeder::class);
        $this->call(UserLevelRolesSeeder::class);
        $this->call(MessageSeeder::class);

        $this->call(TagSeeder::class);
    }
}
