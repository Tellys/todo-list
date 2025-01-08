<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Tag::insert(
            [
                [
                    'name' => 'normal',
                    'created_at' => now(),
                    'color' => 'blue',
                    'user_id'=>1,
                ],
                [
                    'name' => 'urgent',
                    'created_at' => now(),
                    'color' => 'red',
                    'user_id'=>1,
                ],
                [
                    'name' => 'critical',
                    'created_at' => now(),
                    'color' => 'silver',
                    'user_id'=>1,
                ],
            ]
        );
    }
}
