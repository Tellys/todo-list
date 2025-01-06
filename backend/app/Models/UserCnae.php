<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserCnae extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'type',
        'cnae_id',
        'cliente_id',
        'created_at',
        'updated_at',
        'user_id',
    ];

    /**
     * var campos
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['nullable', 'min:5'],
                'label' => 'Nome do Banner',
                'placeholder' => 'Nome do Banner',
                'minlength' => 5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'type' => [
            'html' => [
                'fieldType' => 'seletc',
                //'validation' => 'date',
                'label' => 'Data da Criação',
                'options' => ['fiscal' => 'fiscal', 'secundario' => 'secundario'],
            ],
            'db' => [
                'type' => 'string',
                'default'=> 'secundario',
                'nullable' => true,
            ],
        ],
        'cnae_id' => [
            'html' => [
                'fieldType' => 'seletc',
                'label' => 'CNAE',
                'options' => 'db.cnaes',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        'cliente_id' => [
            'html' => [
                'fieldType' => 'seletc',
                'label' => 'Cliente',
                'options' => 'db.users',
            ],
            'db' => [
                'type' => 'integer',
                //'nullable'=>true,
                //'constrained'=>true,
            ],
        ],
        'created_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => 'date',
                'label' => 'Data da Criação',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'updated_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => 'date',
                'label' => 'Data da Atualização',
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
