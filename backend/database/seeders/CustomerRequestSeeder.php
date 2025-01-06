<?php

namespace Database\Seeders;

use App\Models\DiscountPolicy;
use Illuminate\Database\Seeder;

class CustomerRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //(new PaymentMethodSeeder())->names
        
        //default importante para relacionamentos CART funcionar
        DiscountPolicy::create([
            'discount_policy_id'=>1,
            'client_id'=>1,
            'user_id'=>1,
            'discount_policy_id'=>1,
        ]);

        //seed test
        //empty
    }
}
