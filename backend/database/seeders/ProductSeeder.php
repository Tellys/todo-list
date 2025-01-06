<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
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
            'products_default_id' => 1,
            'tennis_court_id' => 1,
            'price' => 0,
            'price_promo' => null,
            'user_id' => 1,
        ];

        //seed test
        $nItems = 49;
        $i = 2;
        while ($i <= $nItems) {
            $nn[] = [
                'products_default_id' => 1,
                'tennis_court_id' => $i,
                'price' => rand(20.00, 50.00),
                'price_promo' => null,
                'user_id' => $i,
            ];
            $i++;
        }
        Product::insert($nn);
    }
}
