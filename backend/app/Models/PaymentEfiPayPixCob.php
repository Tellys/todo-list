<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentEfiPayPixCob extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = ['json',];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'txid',
        'status',
        'image',
        'qrcode',
        'http_code',
        'json',
        'customer_request_id',
        'user_id',

        'chave',
        'valor',
        'horario',
        'gn_extras',
        'devolucoes_status',
        'devolucoes_valor',
        'devolucoes',

        'end_to_end_id',
        'solicitacao_pagador',

        'expires_in',
        'created_at',
        'updated_at',
    ];

    /**
     * Variavel com os campos do BD
     *
     * @var array
     */
    public $campos =
    [
        'txid' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Tx id',
                'validation' => ['nullable', 'regex:/^[a-zA-Z0-9]{26,35}$/', 'unique:payment_efi_pay_pix_cobs,txid'],
                'placeholder' => 'Tx id',
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
                'nullable' => true,
            ],
        ],
        'status' => [ // ativo, ??
            'html' => [
                'fieldType' => 'text',
                'label' => 'Status',
                'validation' => ['nullable'],
                'placeholder' => 'Status',
            ],
            'db' => [
                'type' => 'string',
                'unsigned' => true,
            ],
        ],
        'image' => [ // imagem
            'html' => [
                'fieldType' => 'text',
                'label' => 'Imagem do Pix',
                'validation' => ['nullable'],
                'placeholder' => 'Imagem do Pix',
            ],
            'db' => [
                'type' => 'longText',
                'unsigned' => true,
            ],
        ],
        'qrcode' => [ 
            'html' => [
                'fieldType' => 'text',
                'label' => 'Qrcode',
                'validation' => ['nullable'],
                'placeholder' => 'Qrcode',
            ],
            'db' => [
                'type' => 'longText',
                'unsigned' => true,
            ],
        ],
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
                'nullable' => true,
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
        'solicitacao_pagador' => [ // ativo, ??
            'html' => [
                'fieldType' => 'text',
                'label' => 'Solicitacao pagador',
                'validation' => ['nullable'],
                'placeholder' => 'Solicitacao pagador',
            ],
            'db' => [
                'type' => 'string',
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
        'expires_in' => [
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
    ];
}

