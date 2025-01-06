<?php

namespace Database\Seeders;

use App\Models\DiscountPolicy;
use Illuminate\Database\Seeder;

class DiscountPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nn = [];

        //default importante para relacionamentos CART funcionar
        $nn[] = [
            'name' => 'default',
            'description' => 'default',
            'user_id' => 1,
        ];

        //seed test
        $nItems = 5;
        $i = 1;
        while ($i <= $nItems) {
            $nn[] = [
                'name' => fake()->name(),
                'description' => fake()->text(),
                'user_id' => 1,
            ];
            $i++;
        }
        DiscountPolicy::insert($nn);
    }
}
