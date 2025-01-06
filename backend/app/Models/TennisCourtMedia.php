<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtMedia extends NossaModel
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
        'image',
        'description',
        'order',
        'tennis_court_id',
        'user_id',

        'created_at',
        'updated_at',
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
                'label' => 'Nome do arquivo',
                'validation' => ['required'],
                'placeholder' => 'Nome do arquivo',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'image' => [
            'html' => [
                'fieldType' => 'file',
                'label' => 'Arquivo de imagem (foto)',
                'validation' => ['image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'], //|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                'placeholder' => 'Arquivo de imagem (foto)',
                'storagePath' => 'tennisCourtMedia',
                'accept' => 'image/jpg, image/jpeg, image/png, image/gif',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'description' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Description',
                'validation' => ['required'],
                'placeholder' => 'Description do arquivo',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'order' => [
            'html' => [
                'fieldType' => null,
                'label' => 'Ordem de exibição',
                //'validation' => ['min:1'],
                'placeholder' => 'Ordem de exibição do arquivo',
                'min' => "0"
            ],
            'db' => [
                'type' => 'integer',
                'nullable' => true,
                'unsigned' => true,
                'default' => 0
            ],
        ],
        'tennis_court_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Quadra',
                'options' => 'db.tennis_courts',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
                'onDelete' => 'cascade',
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
