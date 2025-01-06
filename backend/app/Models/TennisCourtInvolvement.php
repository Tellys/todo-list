<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtInvolvement extends NossaModel
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
        'tennis_court_involvement_table_id',
        'user_id',
    ];

    //    protected $guarded = [];

    /**
     * Variavel com os campos do BD
     * @var array
     */
    public $campos =
    [
        'tennis_court_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Quadra relacionada',
                'placeholder' => 'Quadra relacionada',
                'validation' => ['required'],
                'options' => 'db.tennis_courts',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
            ],
        ],
        'tennis_court_involvement_table_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Tipo de Envolvimento',
                'placeholder' => 'Tipo de Envolvimento',
                'validation' => ['required'],
                'options' => 'db.tennis_court_involvement_tables',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
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

    public function tennis_court_involvement_table()
    {
        return $this->belongsTo(TennisCourtInvolvementTable::class)->with('icon')->select(['name', 'id', 'description', 'icon_id', 'involvement']);
    }
}
