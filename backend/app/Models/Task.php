<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'title',
        'body',
        'tag_id',
        'time_start',
        'time_end',
        'status',
        'views',

        'user_id',

        'expiration_notices_sent',
        'expires_at',
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
        'title' => [
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
        'body' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Mensagem',
                'placeholder' => 'Mensagem',
                'validation' => ['nullable', 'max:255'],
                'maxlength' => 255,
                //'pattern'=>'[a-zA-Z0-9_-\.]+',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],

        ],
        'tag_id' => [
            'html' => [
                'fieldType' => 'select',
                'options' => 'db.tags',
                'validation' => ['nullable'],
                'label' => 'Tag',
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'constrained' => true,
            ],
        ],
        'time_start' => [
            'html' => [
                'fieldType' => 'date',
                'label' => 'Data Início',
                'placeholder' => 'Data Início',
                'validation' => ['required'],
                //'columns' => ['container' => 6],
            ],
            'db' => [
                'type' => 'timestamp',
            ],
        ],
        'time_end' => [
            'html' => [
                'fieldType' => 'date',
                'label' => 'Data Fim',
                'placeholder' => 'Data Fim',
                //'validation' => ['required','lt:time_start'],
                'validation' => ['required'],
                //'columns' => ['container' => 6],
            ],
            'db' => [
                'type' => 'timestamp',
            ],
        ],
        'status' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Status',
                'options' => ['default', 'open', 'closed', 'paused'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default'=>'default',
            ],
        ],
        'views' => [
            'html' => [
                'fieldType' => NULL,
                'label' => 'Visualizações',
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
        'expires_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => 'date',
                'label' => 'Data da Expiração',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'expiration_notices_sent' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => ['nullable', 'number'],
                'label' => 'Avisos de expiração enviados',
            ],
            'db' => [
                'type' => 'int',
                'nullable' => true,
            ],
        ],
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);//->select(['id', 'name']);
    }
}

