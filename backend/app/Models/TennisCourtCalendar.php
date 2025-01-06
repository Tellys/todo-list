<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtCalendar extends NossaModel
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
        'tennis_court_id',
        'tennis_court_opening_hour_id',
        'time_start',
        'time_end',
        'status',
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
        'tennis_court_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Tipo de Descrição',
                'validation' => ['required'],
                'options' => 'db.tennis_courts',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
            ],
        ],
        'tennis_court_opening_hour_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Horário de funcionamento',
                'validation' => ['required'],
                'options' => 'db.tennis_court_opening_hours',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
            ],
        ],
        'time_start' => [
            'html' => [
                'fieldType' => 'datetime',
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
                'fieldType' => 'datetime',
                'label' => 'Data Fim',
                'placeholder' => 'Data Fim',
                'validation' => ['required','lt:time_start'],
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
                'options' => ['in_cart','awaiting_payment', 'paid', 'canceled'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default'=>'in_cart',
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
}
