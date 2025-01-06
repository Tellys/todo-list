<?php

namespace Database\Seeders;

use App\Models\TennisCourtDescriptionTable;
use Illuminate\Database\Seeder;

class TennisCourtDescriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        TennisCourtDescriptionTable::insert([

            [
                'name' => 'Empresta Raquetes',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 306,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Empresta Bolinhas',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 306,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Estacionamento Privado',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Bar',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Internet WiFi',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Placar Eletrônico',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Placar Comum',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Vende Bebida Alcóolica',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Vende Alimento',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 90,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Banheiro Masculino',
                'unit' => 'unidade(s)',
                'user_id' => 1,
                'icon_id' => 54,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Banheiro Feminimo',
                'unit' => 'unidade(s)',
                'user_id' => 1,
                'icon_id' => 54,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Banheiro Unisex',
                'unit' => 'unidade(s)',
                'user_id' => 1,
                'icon_id' => 48,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Banho Quente',
                'unit' => 'unidade(s)',
                'user_id' => 1,
                'icon_id' => 106,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Banho Frio',
                'unit' => 'unidade(s)',
                'user_id' => 1,
                'icon_id' => 106,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Ducha Aberta',
                'unit' => 'unidade(s)',
                'user_id' => 1,
                'icon_id' => 106,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Sistema de Câmeras',
                'unit' => 'sim/não',
                'user_id' => 1,
                'icon_id' => 352,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
            [
                'name' => 'Arquibancada',
                'unit' => 'lugares',
                'user_id' => 1,
                'icon_id' => 352,
                'created_at' => now(),
                'score' => rand(1, 100),
            ],
        ]);
    }
}
