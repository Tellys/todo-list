<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CartSetIsExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cart-set-is-expired-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cart expiration time command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            (new \App\Http\Controllers\Api\CartController())->setIsExpired();
            $r = 'Carrinho atualizado';
        } catch (\Throwable $th) {
            //throw $th;
            $r = $th->getCode().' - '.$th->getMessage();
        }

        return $this->info('Info: '. $r);
    }
}
