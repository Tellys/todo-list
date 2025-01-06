<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerRequest extends NossaModel
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
        'status',
        'price',
        'price_promo',
        'discount',
        'discount_justification',
        'discount_policy_id',
        'payment_method_id',
        'payment_log',
        'client_id',
        'user_id',

        'deleted_at',
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
        'status' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Status',
                'placeholder' => 'Status',
                'options' => ['shopping', 'awaiting_payment', 'processing', 'paid', 'paymentRefused', 'abandoned', 'canceled'], // comprando, pago, pagamento recusado, abandonado, cancelado, waiting for external response
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default' => 'shopping',
            ],
        ],
        'price' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Preço',
                //'validation' => ['required', 'decimal:10,2'],
                'validation' => ['required', 'min:0,01', 'max:999999999'],
                'placeholder' => 'Preço do Item',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
                'default' => 0.00,
            ],
        ],
        'price_promo' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Preço Promocional',
                //'validation' => ['decimal:10,2'],
                'validation' => ['nullable', 'min:0,01', 'max:999999999'],
                'placeholder' => 'Preço Promocional',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'discount' => [
            'html' => [
                'fieldType' => 'number',
                'label' => 'Desconto extra',
                //'validation' => ['decimal:10,2'],
                'validation' => ['nullable', 'min:0,01', 'max:999999999'],
                'placeholder' => 'Desconto extra',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'discount_justification' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Justificativa Desconto',
                'validation' => ['nullable'],
                'placeholder' => 'Justificativa Desconto',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'discount_policy_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Políticas de Desconto',
                'placeholder' => 'Políticas de Desconto',
                'validation' => ['nullable'],
                'options' => 'db.discount_policies',
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'constrained' => true,
            ],
        ],
        'payment_method_id' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Forma de Pagamento',
                'label' => 'Forma de Pagamento',
                'options' => 'db.payment_methods',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        // 'payment_log' => [
        //     'html' => [
        //         'fieldType' => 'longText',
        //         'label' => 'Log de Pagamento',
        //         'placeholder' => 'Log de Pagamento',
        //     ],
        //     'db' => [
        //         'type' => 'longText',
        //         'nullable' => true,
        //     ],
        // ],
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
        'created_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => 'date',
                'label' => 'Data da Criação',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'updated_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => 'date',
                'label' => 'Data da Atualização',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'deleted_at' => [
            'html' => [
                'fieldType' => NULL,
                'validation' => 'date',
                'label' => 'Data da Exclusão',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
    ];

    public $addIncludeTablesRelations = ['cart'];

    public function cart()
    {
        return $this->hasMany(Cart::class)->with(['tennis_court_calendar','product', 'payment_method']);
    }

    public function payment_efi_pay_pix_webhook()
    {
        return $this->hasMany(PaymentEfiPayPixWebhook::class)->latest();
    }
}
