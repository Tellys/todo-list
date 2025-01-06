<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TennisCourtCalendarSetIsExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tennis-court-calendar-set-is-expired-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tennis court calendar expiration time command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            (new \App\Http\Controllers\Api\TennisCourtCalendarController())->setIsExpired();
            $r = 'DB atualizado';
        } catch (\Throwable $th) {
            //throw $th;
            $r = $th->getCode().' - '.$th->getMessage();
        }

        return $this->info('Info: '. $r);
    }
}
