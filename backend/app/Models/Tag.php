<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'name',
        'color',
        'user_id',
    ];
    /**
     * Variavel com os campos do BD
     *
     * ex. validation     'title' => 'required|unique:posts|max:255',
     *
     * @var array
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                //'validation' => ['nullable', 'min:5', 'unique:messages,nane'],
                'validation' => ['nullable', 'min:5'],
                'label' => 'Título',
                'placeholder' => 'Título',
                //'minlength' => 5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
        ],
        'color' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['nullable', 'min:3'],
                'label' => 'Cor',
                'placeholder' => 'Cor',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
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
        ]
    ];
}

