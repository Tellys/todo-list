<?php

namespace App\Providers;

use App\Models\TennisCourt;
use App\Observers\TennisCourtObserver;
use Illuminate\Support\ServiceProvider;

class TennisCourtModelServiceProvider extends ServiceProvider
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
        //
        TennisCourt::observe(TennisCourtObserver::class);
    }
}
