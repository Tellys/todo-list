<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(["prefix" => "api", "middleware" => ["auth:sanctum"]]);

        //Broadcast::routes();
        // //Broadcast::routes(["prefix" => "api", "middleware" => ["auth:sanctum"]]);
        // if(request()->hasHeader('authorization')) {
        //     //Broadcast::routes(["middleware" => "auth:api"]); //is for the api clients requests(React Native App in my case)
        //     Broadcast::routes(["prefix" => "api", "middleware" => ["auth:sanctum"]]);
        // } else {
        //     Broadcast::routes();//is for the web requests
        // }


        require base_path('routes/channels.php');
    }
}
