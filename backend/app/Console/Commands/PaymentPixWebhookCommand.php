<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PaymentPixWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-pix-webhook-command {items}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment Pix Webhook Update BD';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = json_decode(base64_decode($this->argument('items')));

        if (!$items->pix[0]) {
            return $this->info('Error: retorno do banco incorreto!');
        }
        
        $items = (array) $items->pix[0];
        
        $this->info('Tarefa de fundo sendo executada...');
        $r = (new \App\Http\Controllers\Api\CustomerRequestController())->receiverPixWebhook($items);

        if ($r == 'true') {
            $this->info('Ok: Payment Pix Webhook Update BD ');
            return $this->info('Pronto');
        }
 
        return $this->info('Info: '. $r);
    }
}
