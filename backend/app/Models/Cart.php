<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends NossaModel
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
        //'status',
        'tennis_court_calendar_id',
        'product_id',
        'product_name',
        'qty',
        'price',
        'price_promo',
        'discount',
        'discount_justification',
        'discount_policy_id',
        'customer_request_id',
        'client_id',
        'user_id',

        'expires_at',

        'deleted_at',
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
        // 'status' => [ // não mais necessário. O controle passa a ser o softdelete
        //     'html' => [
        //         'fieldType' => 'select',
        //         'label' => 'Status',
        //         'placeholder' => 'Status',
        //         //'options' => ['shopping', 'paid', 'paymentRefused', 'abandoned', 'canceled', 'waiting'], // comprando, pago, pagamento recusado, abandonado, cancelado, waiting for external response
        //         'options' => ['paid', 'awaiting_payment', null], // 'null' para o caso de qualquer ação diferente de 'paid'
        //     ],
        //     'db' => [
        //         'type' => 'string',
        //         'nullable' => true,
        //         'default' => null,
        //     ],
        // ],
        'tennis_court_calendar_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Horário de Quadra',
                'placeholder' => 'Horário de Quadra',
                'validation' => ['nullable'],
                'options' => 'db.products',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
                'nullable' => true,
            ],
        ],
        'product_id' => [
            'html' => [
                'fieldType' => 'select',
                'label' => 'Produto',
                'placeholder' => 'Produto',
                'validation' => ['nullable'],
                'options' => 'db.products',
            ],
            'db' => [
                'type' => 'foreignId',
                'constrained' => true,
                'nullable' => true,
            ],
        ],
        'product_name' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Nome do Produto',
                'placeholder' => 'Nome do Produto',
                'validation' => ['nullable'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'qty' => [
            'html' => [
                'fieldType' => 'Number',
                'label' => 'Qtd',
                'placeholder' => 'Qtd',
                'validation' => ['required'],
                'options' => 'db.products',
                'min' => "0"
            ],
            'db' => [
                'type' => 'decimal',
                'unsigned' => true,
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
        'expires_at' => [
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

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id')->select(['id', 'name', 'cpf', 'email', 'cell_phone']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'name', 'cpf', 'email', 'cell_phone']);
    }

    public static function scopeGetLastCartValid($query, $clientId = null)
    {
        $clientId = $clientId ?? auth('sanctum')->user()->id;

        $where = [
            ['client_id', $clientId],
            ['updated_at', '>=', date('Y-m-d H:i', strtotime('-1 days'))], // last 24hs
            ['customer_request_id', null]
            //['status', null] // não mais necessário. O controle passa a ser o softdelete 
        ];

        return $query->where($where); //->latest('updated_at')->first();
    }

    //
    public static function scopeGetLastCartValidOrCreate($query, $id = null, $clientId = null)
    {

        return $query::getLastCartValid($id, $clientId)->updateOrCreate(
            [
                'client_id' => $clientId,
                'user_id' => auth('sanctum')->user()->id,
            ]
        )->latest('updated_at')->first();
    }

    public static function scopeGetSunCart($query)
    {
        return $query->selectRaw('SUM(IFNULL(price_promo, price)) AS total_price')->value('total_price');
    }
}
