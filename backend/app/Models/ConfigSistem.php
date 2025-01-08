<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigSistem extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'name',
        'value',
        'user_id',
    ];

    /**
     * Variavel com os campos do BD
     * @var array
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Nome da Diretiva do Sistema',
                'placeholder' => 'Nome da Diretiva do Sistema',
                //'validation' => ['required', 'max:255', 'unique:config_sistems,name'],
                'validation' => ['required', 'max:255'],
                /* 'options' => [
                    "webSiteName"=>"Nome do Web Site",
                    "webSiteDescription"=>"Descrição do Web Site",
                    "lang"=>"Linguagem do Web Site (ex. pt-BR)",
                    "langIso"=>"Linguagem ISO do Web Site (ex. pt_BR)",
                    "publisher"=>"Publisher (Nickname Facebook)",
                    "fbAppId"=>"Nº id Facebook",
                    "webSiteTwitterCard"=>"Tipo de Card Tw",
                    "webSiteTwitterAccount"=>"@suaContaTwitter",
                    "webSiteEMail"=>"E-mail do sistema",
                    "webSiteWhatsApp"=>"WhatsApp do sistema",
                ], */
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
            ],
        ],
        'value' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Valor/Conteúdo',
                'placeholder' => 'Valor/Conteúdo',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'text',
            ],
        ],
        'user_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Responsável',
                'options' => 'db.users',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
            ],
        ],
    ];
}
