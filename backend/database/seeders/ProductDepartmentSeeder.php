<?php

namespace Database\Seeders;

use App\Models\ProductDepartment;
use Illuminate\Database\Seeder;

class ProductDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departament = ['quadras', 'equipamentos', 'cantina', 'outros'];
        $r = [];
        foreach ($departament as $key => $value) {
            $r[] = [
                'name' => $value,
                'description' => fake()->text(),
                'user_id' => 1,
            ];
        }
        ProductDepartment::insert($r);
    }
}
