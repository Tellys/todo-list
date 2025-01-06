<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtOpeningHour extends NossaModel
{
    use SoftDeletes;

    protected $casts = [
        'start_time' => 'date:hh:mm',
        'end_time' => 'date:hh:mm',
        //'purchase_expiration_time'=> 'time:H:i'
    ];

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'day',
        'hour_start',
        'hour_end',
        'price',
        'price_promo',
        'user_id',
        'tennis_court_id',
        'purchase_expiration_time',

        'created_at',
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
        'day' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Dia',
                'validation' => ['required'],
                'placeholder' => 'Dia',
                'options' => ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
                'columns' => ['container' => 4],
            ],
            'db' => [
                'type' => 'string',
                //'nullable' => true,
            ],
        ],
        'hour_start' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'H início',
                'validation' => ['required', 'min:1', 'max:24', 'numeric', 'lt:hour_end'], //lt = less than (menor que)
                'placeholder' => 'Horário Início',
                'step' => 1,
                'min' => 1,
                'max' => 24,
                'maxlength' => 2,
                'columns' => ['container' => 3],
            ],
            'db' => [
                'type' => 'string',
                //'nullable' => true,
            ],
        ],
        'hour_end' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'H fim',
                'validation' => ['required', 'min:1', 'max:24', 'numeric'],
                'placeholder' => 'Horário Fim',
                'step' => 1,
                'min' => 1,
                'max' => 24,
                'maxlength' => 2,
                'columns' => ['container' => 3],
            ],
            'db' => [
                'type' => 'string',
                //'nullable' => true,
            ],
        ],
        'price' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Preço',
                //'validation' => ['required', 'decimal:10,2'],
                'validation' => ['required', 'min:0,01', 'max:999999999'],
                'placeholder' => 'Preço do Item',
                'min' => "0",
                'columns' => ['container' => 6],
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'price_promo' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Preço Promocional',
                //'validation' => ['decimal:10,2'],
                'validation' => ['nullable', 'min:0,01', 'max:999999999', 'lt:price'],
                'placeholder' => 'Preço Promocional',
                'min' => "0",
                'columns' => ['container' => 6],
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'purchase_expiration_time' => [
            'html' => [
                'fieldType' => 'time',
                'label' => 'Tempo de expiração da compra',
                'validation' => ['required', 'date_format:H:i'],
                'placeholder' => 'Tempo de expiração da compra',
                'step' => 1,
                'min' => 1,
                'max' => 24,
                'maxlength' => 2,
                //'columns' => ['container' => 3],
            ],
            'db' => [
                'type' => 'time',
                'default' =>'00:15',
                'unsigned' => true,
            ],
        ],
        'tennis_court_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Quadra',
                'options' => 'db.tennis_courts',
                'validation' => ['required'],
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
                'onDelete' => 'cascade',
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

    public static function scopeCreateTennisCourtOpeningHoursBase($query, $id)
    {
        //$day = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
        $day = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $nn = [];
        foreach ($day as $k => $v) {
            $nn[] = [
                //'day' => date('l', $start_time),
                'day' => $v,
                'hour_start' => 8,
                'hour_end' => 23,
                'tennis_court_id' => $id,
                'user_id' => auth('sanctum')->user()->id,
                'created_at' => now(),
            ];
        }

        return $query->insert($nn);
    }

    public static function scopeNumberOfHoursPerDay($query, $tennis_court_id)
    {
        return $query->selectRaw('SUM(hour_end - hour_start) AS soma_diferencas, day')
            ->where('tennis_court_id', $tennis_court_id)
            ->groupBy('day');
    }

    ///
    public static function scopeIsEnable($query, $data, $sendData = false)
    {
        $strtotime = strtotime($data['date']);

        $r = $query
            ->where('tennis_court_id', $data['tennis_court_id'])
            ->where('day', strtolower(date('l', $strtotime)))
            ->where('hour_start', '>=', date('H', $strtotime))
            ->where('hour_end', '>', date('H', $strtotime))
            ;

        if (!$sendData) {
            return $r->count() ? true : false;
        }

        return $r;
    }

    /* public function getStartTimeAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('hh:mm');
    }

    public function getEndTimeAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('hh:mm');
    } */
}
