<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nn = []; 
        $names = ['pix', 'cartão de credito', 'boleto'];
        $types = ['pix','cartao_de_credito', 'boleto'];
        $paymentMethodController = ['\App\Http\Controllers\Api\PaymentEfiPay\PaymentEfiPayPixCobController', null, null];

        //seed test
        foreach ($names as $key => $value) { 
            $nn[] = [
                'name'=>$value,
                'type'=>$types[$key],
                //'status'=>,
                'payment_method_controller'=> $paymentMethodController[$key],
                'financial_institution'=>'Nome do Banco',
                'rate'=>rand(1,10),
                'deadline_for_receipt'=> rand(15,30),
                'description'=>'Descrição do tipo de pagamento',
                'user_id' => 1,
            ];
        }

        PaymentMethod::insert($nn);
    }
}
