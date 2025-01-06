<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtGroup extends NossaModel
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
        'user_id',
        'tennis_court_type_id',
        'tennis_court_description_table_id',
        'description',
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
                'fieldType' => 'text',
                'label' => 'Nome do Grupo: Ex.: Rural/Residencial',
                //'validation' => ['required', 'max:255', 'unique:tennis_court_groups,name'],
                'validation' => ['required', 'max:255'],
                'placeholder' => 'Nome do Grupo: Ex.: Rural/Residencial',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
        ],
        'tennis_court_type_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Tipo de Quadra',
                'options' => 'db.tennis_court_types',
                'validation' => ['required'],
                'placeholder' => 'Tipo de Imóvel',
                'events'=>['onchange'=>'(function(text){console.log(text);})(["Hello World"])']
                //'events'=>['onchange'=>'testalert()']
            ],
            'db' => [
                'options' => 'db.tennis_court_types',
                'type' => 'foreignId',
                'constrained' => true,
                'onDelete' => 'cascade'
            ],
        ],
        'tennis_court_description_table_id' => [
            'html' => [
                'fieldType' => 'checkbox',
                'label' => 'Lista de Itens Descritivos dos Imóveis',
                'options' => 'db.tennis_court_description_tables',
                //'validation' => ['required'],
                'placeholder' => 'Tipo de Imóvel',
            ],
            'db' => [
                'options' => 'db.tennis_court_description_table',
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'description' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Descrição',
                'placeholder' => 'Faça uma descrição prévia de seu conteúdo',
                'validation' => ['required', 'max:255'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default'=>'vazio',
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
