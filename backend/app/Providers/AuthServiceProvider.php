<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable) {
            $url = env('FRONTEND_URL_USER_SEND_VERIFY_EMAIL') . '/' .
                urlencode(
                    URL::temporarySignedRoute(
                        'verification.verify',
                        Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                        [
                            'id' => $notifiable->getKey(),
                            'hash' => sha1($notifiable->getEmailForVerification()),
                        ]
                    )
                );

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->markdown('emails.verify', ['url' => $url]);

            // // no caso de não usar um template customizado
            // ->line('Click the button below to verify your email address.')
            // ->action('Verify Email Address', $spaUrl);
        });
    }
}
