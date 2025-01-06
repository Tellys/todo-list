<?php

namespace App\Providers;

use App\Rules\CnpjRuleValidation;
use App\Rules\CpfCnpjRuleValidation;
use App\Rules\CpfRuleValidation;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Overwrite the default url for password resets.
        ResetPassword::createUrlUsing(
            function ($notifiable, string $token) {
                return env('FRONTEND_URL_USER_RESET_PASSWORD') .'/'. $token . '/' . base64_encode($notifiable->email);
                //return env('FRONTEND_URL_USER_RESET_PASSWORD') .'/'. $token;
            }
        );

        Validator::extend('cpfValidation', function ($attribute, $value, $parameters, $validator) {
            return (new CpfRuleValidation)->passes($attribute, $value);
        }, (new CpfRuleValidation)->message());

        Validator::extend('cnpjValidation', function ($attribute, $value, $parameters, $validator) {
            return (new CnpjRuleValidation)->passes($attribute, $value);
        }, (new CnpjRuleValidation)->message());

        Validator::extend('cpfCnpjValidation', function ($attribute, $value, $parameters, $validator) {
            return (new CpfCnpjRuleValidation)->passes($attribute, $value);
        }, (new CpfCnpjRuleValidation)->message());

        
    }
}
