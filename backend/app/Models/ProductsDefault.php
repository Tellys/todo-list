<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsDefault extends NossaModel
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
        'product_department_id',
        'unit',
        'user_id',
        'image',

        'created_at',
        'updated_at',
    ];

    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Nome do Produto',
                //'validation' => ['required','unique:products,nane'],
                'validation' => ['required'],
                'placeholder' => 'Nome do Produto',
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
                'label' => 'Descrição',
                'validation' => ['required'],
                'placeholder' => 'Description do Produto',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'product_department_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Departamento',
                'options' => 'db.product_departments',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
            ],
        ],
        'unit' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Unidade de Medida',
                'validation' => ['required', 'max:255'],
                'placeholder' => 'Unidade de Medida',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'image' => [
            'html' => [
                'fieldType' => 'file',
                'validation' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'], //|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                //'validation'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                'label' => 'Imagem',
                'placeholder' => 'Imagem',
                'storagePath' => 'product',
                'accept' => 'image/jpg, image/jpeg, image/png, image/gif',
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
