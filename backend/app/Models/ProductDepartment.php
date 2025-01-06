<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDepartment extends NossaModel
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
        'description',
        'user_id',

        'created_at',
        'updated_at',
    ];

    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Nome do Departamento',
                //'validation' => ['required','unique:product_departments,nane'],
                'validation' => ['required'],
                'placeholder' => 'Nome do Departamento',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
        ],
        'description' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Description',
                'validation' => ['required'],
                'placeholder' => 'Description do Departamento',
            ],
            'db' => [
                'type' => 'string',
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
                'constrained' => true,
            ],
        ],
        'created_at' => [
            'html' => [
                'fieldType' => null,
                'validation' => ['nullable', 'date'],
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
                'fieldType' => null,
                'validation' => ['nullable', 'date'],
                'label' => 'Data da Atualização',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],

        ]
    ];
}
