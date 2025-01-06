<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentEfiPay extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* public $fillable = [
        'id',
        'http_code',
        'json',
        'customer_request_id',
        'client_id',
        'user_id',
    ]; */

    /**
     * Variavel com os campos do BD
     *
     * @var array
     */
    /* public $campos =
    [
        'http_code' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Http Status',
                'validation' => ['nullable', 'min:1', 'max:999999999'],
                'placeholder' => 'Http Status',
                'min' => "0"
            ],
            'db' => [
                'type' => 'integer',
                'unsigned' => true,
            ],
        ],
        'json' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Json return',
                'placeholder' => 'Json return',
                'validation' => ['nullable'],
            ],
            'db' => [
                'type' => 'longText',
                'nullable' => true,
            ],
        ],
        'customer_request_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Nº Peidod',
                'placeholder' => 'Nº Peidod',
                'validation' => ['nullable'],
                'options' => 'db.customer_requests',
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'constrained' => true,
            ],
        ],
        'client_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Cliente',
                'placeholder' => 'Cliente',
                'options' => 'db.users',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        'user_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Responsável',
                'placeholder' => 'Responsável',
                'options' => 'db.users',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
    ]; */
}
