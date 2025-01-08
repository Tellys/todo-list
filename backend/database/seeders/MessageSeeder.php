<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nItems = 50;
        $i = 1;
        $nn = [];
        while ($i <= $nItems) {
            $uniq = uniqid();
            $nn[] = [
                'title'      => $uniq . ' Cliente Test',
                'body' => $uniq . 'Body Message Teste 01 Message Teste 01 Message Teste 01 Message Teste 01 Message Teste 01 Message Teste 01 Message Teste 01 Message Teste 01 Message Teste 01',
                'user_id' => rand(1,4),
                'created_at' => now(),
            ];
            $i++;
        }

        Message::insert($nn);
    }
}
