<?php

namespace App\Providers;

use App\Models\TennisCourtMedia;
use App\Observers\TennisCourtMediaObserver;
use Illuminate\Support\ServiceProvider;

class TennisCourtMediaModelServiceProvider extends ServiceProvider
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
        TennisCourtMedia::observe(TennisCourtMediaObserver::class);

    }
}
