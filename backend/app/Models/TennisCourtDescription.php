<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtDescription extends NossaModel
{
    use SoftDeletes ; 

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'tennis_court_description_table_id',
        'tennis_court_id',
        'value',
        'user_id',
    ];

    //public $addIncludeTablesRelations = ['tennis_court_description'];

    /**
     * Variavel com os campos do BD
     *
     * ex. validation     'title' => 'required|unique:posts|max:255',
     *
     * @var array
     */
    public $campos =
    [
        'tennis_court_description_table_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Tipo de Descrição',
                //'validation' => ['required'],
                'options' => 'db.tennis_court_description_tables',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        'tennis_court_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Imóvel ID',
                //'validation' => ['required'],
                'options' => 'db.tennis_courts',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        'value' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Valor',
                //'validation' => ['required', 'decimal:10,2'],
                //'validation' => ['required'],
                'placeholder' => 'Valor do Item',
            ],
            'db' => [
                'type' => 'string',
                'unsigned'=>true,
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
    ];
}
