<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtInvolvementTable extends NossaModel
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
        'involvement',
        'icon_id',
        'description',
        'user_id',
    ];

    //    protected $guarded = [];

    /**
     * Variavel com os campos do BD
     * @var array
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Reações do item',
                'options' => ['like', 'save'],
                'placeholder' => 'Reações do item',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => false,
            ],
        ],
        'involvement' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Tipo de Envolvimento',
                'placeholder' => 'Ex: like, save',
                'validation' => ['nullable', 'max:255'],
                'maxlength' => 255,
                //'pattern'=>'[a-zA-Z0-9_-\.]+',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'icon_id' => [
            'html' => [
                'fieldType' => 'radio',
                'label' => 'Icone',
                'options' => 'db.icons',
                'placeholder' => 'Icone do Item',
                'icons' => true,
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'default' => 106,
                'constrained' => true,
            ],
        ],
        'description' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Sua Descrição resumida',
                'placeholder' => 'Faça uma descrição resumida. Coloque apenas o mais importante',
                'validation' => ['nullable', 'max:255'],
                'maxlength' => 255,
                //'pattern'=>'[a-zA-Z0-9_-\.]+',
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
    ];
}
