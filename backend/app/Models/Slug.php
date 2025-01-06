<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slug extends NossaModel
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'redirect',
        'type',
        'views',
        'user_id',
        'module',
        'module_id',
    ];

    //https://laravel.com/docs/master/validation#rule-alpha
    // link para documentação laravel de validacao

    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['required', 'min:4', 'alpha_dash'],
                'label' => 'URL do sistema',
                'placeholder' => 'http://www.meusite.com.br/minha-url-personalisada',
                'minlength' => 4,
            ],
            'db' => [
                'type' => 'string',
            ],
        ],
        'redirect' => [
            'html' => [
                'fieldType' => 'text',
                //'validation' => ['required', 'min:4', 'alpha_dash','unique:slugs,redirect'],
                'validation' => ['required', 'min:4', 'alpha_dash'],
                'label' => 'Slug (url personalisada)',
                'placeholder' => 'http://www.meusite.com.br/minha-url-personalisada',
                'minlength' => 4,
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
            ],
        ],
        'type' => [
            'html' => [
                'fieldType' => 'number',
                'validation' => ['min:1', 'max:999'],
                'label' => 'Tipo de redirecionamento',
                'placeholder' => 'Ex.: 301',
                'minlength' => 3,
                'min' => "0"
            ],
            'db' => [
                'type' => 'integer',
                'nullable' => true,
                'default' => 301,
                'unsigned' => true,
            ],
        ],
        'views' => [
            'html' => [
                'fieldType' => NULL,
                'label' => 'Visualizações',
                //'validation' => ['decimal:10'],
                'validation' => ['min:1'],
                'placeholder' => 'Visualizações',
            ],
            'db' => [
                'type' => 'tinyInteger',
                'nullable' => true,
                'default' => 0,
                'unsigned' => true,
            ],
        ],
        'module' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['required'],
                'label' => 'Módulo',
                'placeholder' => 'Módulo',
            ],
            'db' => [
                'type' => 'string',
            ],
        ],
        'module_id' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Imóvel ID',
                'validation' => ['required'],
                'min' => "0"
            ],
            'db' => [
                'type' => 'integer',
                'unsigned' => true,
            ],
        ],
        'created_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => ['date'],
                'label' => 'Data da Criação',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'updated_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => ['date'],
                'label' => 'Data da Atualização',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
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
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],

    ];
}
