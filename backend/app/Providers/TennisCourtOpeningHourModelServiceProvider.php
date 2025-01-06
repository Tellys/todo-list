<?php

namespace App\Providers;

use App\Models\TennisCourtOpeningHour;
use App\Observers\TennisCourtOpeningHourObserver;
use Illuminate\Support\ServiceProvider;

class TennisCourtOpeningHourModelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        TennisCourtOpeningHour::observe(TennisCourtOpeningHourObserver::class);
    }
}
