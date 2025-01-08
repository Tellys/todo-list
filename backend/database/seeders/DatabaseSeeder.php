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
        $this->call(LocationSeeder::class);

        $this->call(TennisCourtTypeSeeder::class);        
        $this->call(TennisCourtGroupSeeder::class);        
        $this->call(TennisCourtDescriptionTableSeeder::class);

        $this->call(TennisCourtSeeder::class);

        $this->call(TennisCourtOpeningHourSeeder::class);
        $this->call(TennisCourtCalendarSeeder::class);      
        $this->call(TennisCourtDescriptionSeeder::class);      
        
        $this->call(ConfigSistemSeeder::class);
        $this->call(UserLevelRolesSeeder::class);
        $this->call(MessageSeeder::class);
        $this->call(TennisCourtMediaSeeder::class);

        $this->call(ProductDepartmentSeeder::class);
        $this->call(ProductsDefaultSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(DiscountPolicySeeder::class);        

        $this->call(TennisCourtInvolvementTableSeeder::class);        
        $this->call(TennisCourtInvolvementSeeder::class);        

        $this->call(PaymentMethodSeeder::class);        

        $this->call(TagSeeder::class);
    }
}
