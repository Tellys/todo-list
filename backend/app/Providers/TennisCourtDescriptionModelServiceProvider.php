<?php

namespace App\Providers;

use App\Models\TennisCourtDescription;
use App\Observers\TennisCourtDescriptionObserver;
use Illuminate\Support\ServiceProvider;

class TennisCourtDescriptionModelServiceProvider extends ServiceProvider
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
        TennisCourtDescription::observe(TennisCourtDescriptionObserver::class);
    }
}
