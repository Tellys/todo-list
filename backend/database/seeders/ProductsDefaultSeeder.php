<?php

namespace Database\Seeders;

use App\Models\ProductsDefault;
use Illuminate\Database\Seeder;

class ProductsDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nItems = 49;
        $i = 1;
        $nn[] = [
            'name' => 'Aluguel de Quadra',
            'description' => 'Descrição do Aluguel de Quadra',
            'product_department_id' => 1,
            'unit' => 'hora',
            'user_id' => $i,
        ];

        while ($i <= $nItems) {
            $nn[] = [
                'name' => fake()->name(),
                'description' => fake()->text(),
                'product_department_id' => rand(1, 3),
                'unit' => 'un',
                'user_id' => 1,
            ];
            $i++;
        }

        ProductsDefault::insert($nn);
    }
}
