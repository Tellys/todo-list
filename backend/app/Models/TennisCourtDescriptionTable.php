<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtDescriptionTable extends NossaModel
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
        'icon_id',
        'user_id',
        'unit',
        'score',
    ];

    /**
     * @var array
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Nome do Item',
                //'validation' => ['required', 'max:255', 'unique:tennis_court_description_tables,name'],
                'validation' => ['required', 'max:255'],
                'placeholder' => 'Nome do Item',
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
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
        'unit' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Unidade de Medida',
                'validation' => ['required', 'max:255',],
                'placeholder' => 'Unidade de Medida',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'score' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Score',
                'validation' => ['nullable', 'min:1'],
                'placeholder' => 'Score',
            ],
            'db' => [
                'type' => 'integer',
                'nullable' => true,
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
    ];


    public function camposMasterOptions()
    {
        $r = $this->orderBy('name', 'DESC')->get(['id', 'name'])->toArray();
        $this->campos['master']['options'] = $r;
    }
}
