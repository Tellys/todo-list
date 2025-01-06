<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends NossaModel
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
        'type',
        'status',
        'payment_method_controller',
        'financial_institution',
        'rate',
        'deadline_for_receipt',
        'description',
        'user_id',
    ];

    /**
     * Variavel com os campos do BD
     *
     * @var array
     */
    public $campos =
    [
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['nullable', 'min:5', 'unique:payment_methods,nane'],
                'label' => 'Nome',
                'placeholder' => 'Nome',
                //'minlength' => 5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
        ],
        'type' => [ //tipo de pagamento
            'html' => [
                'fieldType' => 'select',
                'label' => 'Status',
                'placeholder' => 'Status',
                'options' => ['cartao_de_credito', 'pix', 'boleto'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'status' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Status',
                'placeholder' => 'Status',
                'options' => ['operational', 'suspended'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'default' => 'operational',
            ],
        ],
        'payment_method_controller' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['nullable', 'min:2'],
                'label' => 'Nome DB Método',
                'placeholder' => 'Nome DB Método',
                //'minlength' => 5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'financial_institution' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['nullable', 'min:5'],
                'label' => 'Instituição Financeira',
                'placeholder' => 'Instituição Financeira',
                //'minlength' => 5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'rate' => [ //taxa
            'html' => [
                'fieldType' => 'Number',
                'label' => 'Taxa',
                'placeholder' => 'Taxa',
                'validation' => ['required'],
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'unsigned' => true,
                'nullable' => true,
            ],
        ],
        'deadline_for_receipt' => [ // prazo para recebimento da instituição financeira
            'html' => [
                'fieldType' => 'datetime',
                'label' => 'Data Início',
                'placeholder' => 'Data Início',
                'validation' => ['required'],
                //'columns' => ['container' => 6],
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'description' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Descrição resumida',
                'placeholder' => 'Faça uma descrição resumida. Coloque apenas o mais importante',
                'validation' => ['nullable', 'max:255'],
                'maxlength' => 255,
                //'pattern'=>'[a-zA-Z0-9_-\.]+',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
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
