<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentEfiPayPixKey extends NossaModel
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
        'key',
    ];

    /**
     * Variavel com os campos do BD
     *
     * @var array
     */
    public $campos =
    [
        'key' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Key',
                //'validation' => ['nullable', 'regex:/^[a-zA-Z0-9]{26,35}$/', 'unique:payment_efi_pay_pix_keys,key'],
                'validation' => ['nullable', 'unique:payment_efi_pay_pix_keys,key'],
                'placeholder' => 'Key',
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
                'nullable' => true,
            ],
        ],
    ];
}


