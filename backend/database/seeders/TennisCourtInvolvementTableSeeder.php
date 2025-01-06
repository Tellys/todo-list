<?php

namespace Database\Seeders;

use App\Models\TennisCourtInvolvementTable;
use Illuminate\Database\Seeder;

class TennisCourtInvolvementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TennisCourtInvolvementTable::insert([
            [
                'name' => 'Favorito',
                'involvement' => 'like',
                'icon_id' => 255,
                'description' => 'Favorito',
                'user_id' => 1,
            ],
            [
                'name' => 'Salvar',
                'involvement' => 'save',
                'icon_id' => 66,
                'description' => 'Salvar',
                'user_id' => 1,
            ],

        ]);
    }
}
