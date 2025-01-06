<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'title',
        'body',
        'level',
        'status',
        'views',
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
                'validation' => ['nullable', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
                'maxlength' => 255,
                //'pattern'=>'[a-zA-Z0-9_-\.]+',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],

        ],
        'level' => [
            'html' => [
                'fieldType' => 'select',
                'options' => ['info', 'warning', 'success', 'danger', 'other'],
                'validation' => ['nullable'],
                'label' => 'Tipo de Usuário',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default' => 'info',
            ],
        ],
        'status' => [
            'html' => [
                'fieldType' => 'select',
                'options' => ['unread', 'read', 'deleted', 'other'],
                'validation' => ['nullable'],
                'label' => 'Tipo de Usuário',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default' => 'unread',
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
        'created_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => ['date'],
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
                'validation' => ['date'],
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
                'constrained' => true,
            ],
        ],

    ];

    public static function scopeMessageToIdUser($query, int $userId, array $status = ['status','read'],)
    {
        return $query->where([
            ['user_id', $userId],
            $status,
        ]);
    }
}
