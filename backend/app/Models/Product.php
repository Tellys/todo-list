<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends NossaModel
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
        'products_default_id',
        'price',
        'price_promo',
        'tennis_court_id',
        'user_id',

        'created_at',
        'updated_at',
    ];

    public $campos =
    [
        'products_default_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Nome do Produto',
                'options' => 'db.products_defaults',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
            ],
        ],
        'price' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Preço',
                //'validation' => ['required', 'decimal:10,2'],
                'validation' => ['required', 'min:0,01', 'max:999999999'],
                'placeholder' => 'Preço do Item',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'price_promo' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Preço Promocional',
                //'validation' => ['decimal:10,2'],
                'validation' => ['min:0,01', 'max:999999999'],
                'placeholder' => 'Preço Promocional',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'tennis_court_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Tennis Court ID',
                //'validation' => ['required'],
                'options' => 'db.tennis_courts',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
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

    public static function scopeCreateProductsBase($query, $id)
    {
        return $query->create([
            'products_default_id' => 2, // um produto qualquer
            'price' => 50,
            'price_promo' => null,
            'user_id' => auth('sanctum')->user()->id,
            'tennis_court_id' => $id,
            'created_at' => now(),
        ]);
    }
}
