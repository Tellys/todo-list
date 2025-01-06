<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLevelRoles extends Model
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
        'action',
        'users_level_id',
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
        'users_level_id' => [
            'html' => [
                'fieldType' => 'radio',
                'options' => 'db.users_levels',
                'validation' => ['required'],
                'label' => 'Tipo de Usuário',
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'constrained' => true,
            ],
        ],
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Nome da Regra/Módulo',
                'validation' => ['required', 'max:255'],
                'placeholder' => 'Nome da Regra/Módulo',
                'helpText' => 'É o Módulo @ Ação do sistema'
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default'=>'nome',
            ],
        ] ,
        'action' => [
            'html' => [
                'fieldType' => 'radio',
                'label' => 'Ação Autorizada',
                'validation' => ['required', 'max:255'],
                'placeholder' => 'Ação Autorizada',
                'options' => [
                    'none'=>'Negado',
                    'restrict'=>'Acesso em suas Propriedades',
                    'full'=>'Acesso Geral',
                ],
                'helpText' => 'Negar ou Autorizar a ação'
            ],
            'db' => [
                'type' => 'enum',
                'default' => 'none',
            ],
        ],
        'param' => [
            'html' => [
                'fieldType' => 'radio',
                'label' => 'Parâmeteos da Ação Autorizada',
                'validation' => ['required', 'max:255'],
                'placeholder' => 'Parâmeteos da Ação Autorizada',
                'options' => [
                    0=> 'Meus registros',
                    'users_level_id:<='=>'Acesso a intens de usuário com level menor',
                ],
                'helpText' => 'Parâmetros de acesso restrito'
            ],
            'db' => [
                'type' => 'enum',
                'default' => 0,
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
                'onDelete' => 'cascade'
            ],
        ],
    ];
}
