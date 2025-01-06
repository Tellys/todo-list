<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends  NossaModel
{
    use SoftDeletes;

    protected $casts = [
        'lat' => 'decimal',
        'lng' => 'decimal',
    ];

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'city',
        'neighborhood',
        'lat',
        'lng',
        'geo_point',
        'country',
        'country_short',
        'state',
        'state_short',
        'timezone',
        'user_id',
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
        'city_code' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Cidade cod',
                'validation' => ['nullable', 'min:2'],
            ],
            'db' => [
                'type' => 'integer',
                'nullable' => true,

            ],
        ],
        'city' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Cidade',
                'validation' => ['nullable', 'min:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,

            ],
        ],
        'neighborhood' => [
            'html' => [
                'fieldType' => 'text',
                //                'validation'=> ['required'],
                'label' => 'Bairro',
                'placeholder' => 'Bairro',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'state_code' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Cod Estado',
                'validation' => ['nullable', 'min:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,

            ],
        ],
        'state' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Estado',
                'validation' => ['nullable', 'min:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,

            ],
        ],
        'state_short' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Estado Abreviatura',
                'validation' => ['nullable', 'min:2', 'max:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'country' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'País',
                'validation' => ['required', 'min:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default' => 'Brasil'

            ],
        ],
        'country_code' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'País',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'country_short' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'País Abreviatura',
                'validation' => ['nullable', 'min:2', 'max:10'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,

            ],
        ],

        'lat' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Latitude',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,

            ],
        ],
        'lng' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Longitude',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'decimal', // $table->decimal("lat",11, 8)->nullable();
                'nullable' => true,

            ],
        ],
        //            $table->point("geo_point")->nullable();
        // 'geo_point' => [
        //     'html' => [
        //         'fieldType' => 'string',
        //         'label' => 'País',
        //         'validation' => ['required', 'min:2'],
        //     ],
        //     'db' => [
        //         'type' => 'point',
        //         'nullable' => true,

        //     ],
        // ],
        'timezone' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'Timezone',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default' => 'America/Sao_Paulo'
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
