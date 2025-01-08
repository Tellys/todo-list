<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // https://betterstack.com/community/guides/scaling-php/laravel-task-scheduling/
        // php artisan schedule:run
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:cart-set-is-expired-command')->everyMinute()->runInBackground();

        // estou acrescentando o comando para expiração do calendario a cada 15 minutos
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
