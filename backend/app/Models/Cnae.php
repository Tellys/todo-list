<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Cnae extends NossaModel
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
        'codigo',
        'created_at',
        'updated_at',
        'user_id',
    ];

    /**
     * cod = id
     * name
     * fk_user
     */

    /**
     * var campos
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                //'validation' => ['nullable', 'min:5','unique:cnaes,nane'],
                'validation' => ['nullable', 'min:5'],
                'label' => 'Nome',
                'placeholder' => 'Nome',
                //'minlength' => 5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
        ],
        'codigo' => [
            'html' => [
                'fieldType' => 'text',
                //'validation' => ['nullable', 'unique:cnaes,codigo'],
                'validation' => ['nullable'],
                'label' => 'Código',
                'placeholder' => 'Código',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
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

