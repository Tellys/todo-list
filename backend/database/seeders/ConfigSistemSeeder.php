<?php

namespace Database\Seeders;

use App\Models\ConfigSistem;
use Illuminate\Database\Seeder;

class ConfigSistemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConfigSistem::insert([
            [
                'name' => 'webSiteName',
                'value' => 'Leandro Imobiliária - Três Pontas/MG',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteDescription',
                'value' => 'Compra e Venda de Imóveis em Três Pontas. Casas, Apartamentos, Fazendas, Sítios, Lotes, Terrenos. Financiamento Habitação Caixa, Empréstimos Consignados',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'lang',
                'value' => 'pt-BR',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'langIso',
                'value' => 'pt_BR',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'publisher',
                'value' => 'leandroimobiliariaoficial',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'fbAppId',
                'value' => 'colocar o seu Nº Id App Facebook',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteTwitterCard',
                'value' => 'summary_large_image',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteTwitterAccount',
                'value' => '@LImobiliaria',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteEMail',
                'value' => 'atendimento@leandroimobiliariatp.com.br',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteWhatsApp',
                'value' => '(35) 3266-2055',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSitePhone',
                'value' => '(35) 3266-2055',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLogo',
                'value' => '/assets/public/images/logomarca-leandro-imobiliaria-tres-pontas-minas-gerais.png',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLinkGoogleMaps',
                'value' => 'https://maps.app.goo.gl/qzmNHjeLahiFjGyM9',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLinkTwitter',
                'value' => 'https://twitter.com/LImobiliaria',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLinkFacebook',
                'value' => 'https://www.facebook.com/leandroimobiliariaoficial',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLinkLinkedin',
                'value' => 'https://www.linkedin.com/company/leandro-imobiliaria',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLogradouro',
                'value' => 'Rua Praça Cônego Vítor, 25 - Centro',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLogradouroCep',
                'value' => '37190000',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLogradouroCidade',
                'value' => 'Três Pontas',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'webSiteLogradouroUf',
                'value' => 'MG',
                'user_id' => 1,
                'created_at' => now(),
            ],


        ]);
    }
}
