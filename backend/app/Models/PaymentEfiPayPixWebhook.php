<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentEfiPayPixWebhook extends NossaModel
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
        'end_to_end_id',
        'payment_efi_pay_pix_cob_txid',
        'chave',
        'valor',
        'horario',
        'gn_extras',
        'devolucoes_status',
        'devolucoes_valor',
        'devolucoes',
        'customer_request_id',
        'user_id',
    ];

    /**
     * Variavel com os campos do BD
     *
     * @var array
     */
    public $campos =
    [
        'end_to_end_id' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'EndToEnd Id',
                'validation' => ['nullable', 'regex:/^[a-zA-Z0-9]{26,35}$/', 'unique:payment_efi_pay_pix_webhooks,end_to_end_id'],
                'placeholder' => 'EndToEnd Id',
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
                'nullable' => true,
            ],
        ],
        'payment_efi_pay_pix_cob_txid' => [
            'html' => [
                'fieldType' => 'Tx id',
                'label' => 'Tx id',
                'validation' => ['nullable'],
                'placeholder' => 'Tx id',
                'options' => 'db.payment_efi_pay_pix_cobs',
            ],
            'db' => [ 
                'type' => 'string',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        'chave' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Chave',
                'validation' => ['nullable'],
                'placeholder' => 'Chave',
            ],
            'db' => [
                'type' => 'string',
                //'unique' => true,
                'nullable' => true,
            ],
        ],
        'valor' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Valor',
                'validation' => ['required', 'min:0.00', 'max:999999999'],
                'placeholder' => 'Valor',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
                'default' => 0.00,
            ],
        ],
        'horario' => [
            'html' => [
                'fieldType' => 'date',
                'validation' => 'date',
                'label' => 'Horário',
                'placeholder' => 'Horário',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'gn_extras' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'GN Extras',
                'placeholder' => 'GN Extras',
                'validation' => ['nullable'],
            ],
            'db' => [
                'type' => 'longText',
                'nullable' => true,
            ],
        ],
        'devolucoes_status' => [ // ativo, ??
            'html' => [
                'fieldType' => 'text',
                'label' => 'Status',
                'validation' => ['nullable'],
                'placeholder' => 'Devoluções Status',
            ],
            'db' => [
                'type' => 'string',
                'unsigned' => true,
            ],
        ],
        'devolucoes_valor' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Devoluções Valor',
                'validation' => ['nullable', 'min:0,01', 'max:999999999'],
                'placeholder' => 'Devoluções Valor',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
                'default' => null,
            ],
        ],
        'devolucoes' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Devoluções',
                'placeholder' => 'Devoluções',
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
    ];
}
