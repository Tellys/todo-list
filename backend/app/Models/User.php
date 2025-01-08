<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $dates = ['deleted_at'];
    public $addIncludeTablesRelations = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'id',
        'users_level_id',
        'image',
        'name',
        'name_corporate',
        'state_code',
        'web_site',
        'password',
        'birthday',
        'cpf',
        'slug',
        'email',
        'email_verified_at',
        'state',
        'city',
        'zip_code',
        'address',
        'address_neighborhood',
        'address_num',
        'address_complement',
        'lat',
        'lng',
        //'geo_point',
        'country',
        'country_code',
        'phone',
        'cell_phone',
        'cell_phone_verified_at',
        'created_at',
        'type_login',
        'updated_at',
        'user_id',
        'description',

        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        //'geo_point',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'cell_phone_verified_at' => 'datetime',
        //'lat' => 'decimal',
        //'lng' => 'decimal',
        //'birthday', 'date',
    ];

    public $campos =
    [
        'users_level_id' => [
            'html' => [
                'fieldType' => 'select',
                'options' => 'db.users_levels',
                //'validation' => ['required'],
                'label' => 'Tipo de Usuário',
                'user_level_admin' => true,
                'disabled' => true,
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'constrained' => true,
                'default' => 4,
            ],
        ],
        'image' => [
            'html' => [
                'fieldType' => 'file',
                'validation' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'], //|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                //'validation'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                'label' => 'Foto de Perfil / Logomarca',
                'placeholder' => 'Foto do seu Perfil / Logomarca',
                'storagePath' => 'user',
                'accept' => 'image/jpg, image/jpeg, image/png, image/gif',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'name' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['required', 'min:10'],
                'label' => 'Nome Completo',
                'placeholder' => 'Coloque seu nome completo',
                'minlength' => 10,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                //'invisible' => true, // nao retornará a coluna em nenhuma select
            ],
        ],
        'name_corporate' => [
            'html' => [
                'fieldType' => 'text',
                'validation' => ['nullable', 'min:10'],
                'label' => 'Razão Social',
                'placeholder' => 'Razão Social de sua empresa',
                'minlength' => 10,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                //'invisible' => true, // nao retornará a coluna em nenhuma select
            ],
        ],
        'state_code' => [
            'html' => [
                'fieldType' => 'Estado (UF) cógido',
                'label' => 'Estado (UF) cógido',
                'validation' => ['nullable', 'min:2'],
                'placeholder' => 'Estado (UF)',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'state' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Estado (UF)',
                'validation' => ['nullable', 'min:2'],
                'placeholder' => 'Estado (UF)',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'city' => [
            'html' => [
                'fieldType' => 'text',
                'label' => 'Cidade',
                'validation' => ['nullable', 'min:2', 'max:100'],
                'placeholder' => 'Cidade',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'birthday' => [
            'html' => [
                'fieldType' => 'date',
                'validation' => ['nullable', 'date'],
                'label' => 'Data da Nascimento',
                'min' => '1900-01-01',
                'maxlength' => 10,
            ],
            'db' => [
                'type' => 'dateTime',
                'nullable' => true,
            ],
            //'mask' => '##/##/####',
        ],
        'web_site' => [
            'html' => [
                'fieldType' => 'url',
                'label' => 'Seu web site',
                'placeholder' => 'www.todo-list.app.br',
                //'validation' => ['nullable', 'url','unique:users,web_site'],
                'validation' => ['nullable', 'url'],
            ],
            'db' => [
                'type' => 'string',
                'unique' => true,
                'nullable' => true,
            ],
        ],
        'password' => [
            'html' => [
                'fieldType' => 'password',
                'validation' => ['nullable', 'min:8'],
                //'validation'=> ['nullable','sometimes', 'min:8'],
                'label' => 'Senha (minimo 8 caracteres)',
                'placeholder' => 'Coloque uma senha forte',
                //'minlength'=>5,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                //'default'=> '$2y$10$1hwH2p65zOrn5vfB3HHaqeeZ.p68IKWOdQTwL6R43.DFK/UZitwgW'
            ],
        ],

        'cpf' => [
            'html' => [
                'fieldType' => 'string',
                'label' => 'CPF/CNPJ',
                //'validation' => ['required', 'decimal:10,2', 'min:1', 'max:99999999999999'],
                //'validation' => ['nullable', 'min:11', 'max:18', 'cpfCnpjValidation','unique:users,cpf'],
                'validation' => ['nullable', 'min:11', 'max:18', 'cpfCnpjValidation'],
                'placeholder' => 'CPF/CNPJ',
                'min' => "0"
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
            'mask' => ['nn.nnn.nnn/nnnn-nn', 'nnn.nnn.nnn-nn'],
        ],
        'slug' => [
            'html' => [
                'fieldType' => 'hidden',
                //'validation' => ['nullable', 'min:4', 'url','unique:users,slug'],
                'validation' => ['nullable', 'min:4', 'url'],
                'label' => 'Url do seu Perfil',
                'placeholder' => 'Url do seu Perfil',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                'unique' => true,
            ],
        ],
        'email' => [
            'html' => [
                'fieldType' => 'email',
                'label' => 'Seu e-mail',
                'placeholder' => 'exemplo@email.com',
                //'validation' => ['nullable', 'email:rfc,dns', 'unique:users,email'],
                'validation' => ['nullable', 'email:rfc,dns'],
            ],
            'db' => [
                'type' => 'string',
                //'nullable'=>true,
                'unique' => true,
            ],
        ],
        'email_verified_at' => [
            'html' => [
                'fieldType' => null,
                'label' => 'Email Verificado',
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'zip_code' => [
            'html' => [
                'fieldType' => 'string',
                //                'validation'=> ['required','min:8', 'min:0', 'numeric'],
                'label' => 'CEP',
                'placeholder' => 'Coloque seu CEP',
                'maxlength' => 8,
                'minlength' => 8,
                'min' => 0
                //'onBlur'=>'zeroEsquerda(this,8)',
            ],
            'db' => [
                'type' => 'integer',
                'unsigned' => true,
                'default' =>  '00000000',
                'length' => 8,
                'nullable' => true,
            ],
            'mask' => '##.###-###',
        ],
        'address' => [
            'html' => [
                'fieldType' => 'text',
                //                'validation'=> ['required'],
                'label' => 'Endereço',
                'placeholder' => 'Coloque Rua/Av/Tv',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'address_neighborhood' => [
            'html' => [
                'fieldType' => 'text',
                //                'validation'=> ['required'],
                'label' => 'Bairro',
                'placeholder' => 'Bairro',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'address_num' => [
            'html' => [
                'fieldType' => 'number',
                'validation' => ['nullable', 'numeric'],
                'label' => 'Nº',
                'placeholder' => 'Nº',
                'min' => 0,
            ],
            'db' => [
                'type' => 'integer',
                'nullable' => true,
                'unsigned' => true,
            ],
        ],
        'address_complement' => [
            'html' => [
                'fieldType' => 'text',
                //'validation'=> ['alpha_dash'],
                'label' => 'Complemento',
                'placeholder' => 'Complemento',
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'lat' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Latitude',
                'validation' => ['nullable'],
            ],
            'db' => [
                'type' => 'double',
            ],
        ],
        'lng' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'Longitude',
                'validation' => ['nullable'],
            ],
            'db' => [
                'type' => 'double', // $table->double("lat",11, 8)->nullable();
                'nullable' => true,
            ],
        ],
        // 'geo_point' => [ //$table->point("geo_point")->nullable();
        //     'html' => [
        //         'fieldType' => 'hidden',
        //         'label' => 'Geo Point',
        //         //'validation' => ['nullable', 'min:2'],
        //     ],
        //     'db' => [
        //         'type' => 'point',
        //         'nullable' => true,
        //     ],
        // ],
        'phone' => [
            'html' => [
                'fieldType' => 'tel',
                'validation' => ['nullable', 'min:9', 'min:0'/* , 'numeric' */],
                'label' => 'Telefone Fixo (sem 0 na frente & somente números)',
                'placeholder' => 'XXYYYYZZZZ',
                'maxlength' => 11,
                'minlength' => 9,
                'min' => 0,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                //'unsigned' => true,
                'unique' => true,
            ],
            'mask' => ['(##) ####-####', '(##) # ####-####'],
            //'mask' => 'phone'
        ],
        'cell_phone' => [
            'html' => [
                'fieldType' => 'tel',
                'validation' => ['required', 'min:10'/* , 'numeric' */],
                'label' => 'Celular / WhatsApp (sem 0 na frente & somente números)',
                'placeholder' => 'XXYYYYYZZZZ',
                'maxlength' => 11,
                'minlength' => 11,
                'min' => 0,
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
                //'unsigned' => true,
                'unique' => true,
            ],
            'mask' => ['(##) ####-####', '(##) # ####-####'],
        ],
        'cell_phone_verified_at' => [
            'html' => [
                'fieldType' => null,
                //'validation' => ['required', 'min:10'/* , 'numeric' */],
                'label' => 'Celuar Verificado',
                'placeholder' => 'Celuar Verificado',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],
        ],
        'country' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'País',
                'validation' => ['nullable', 'min:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'country_code' => [
            'html' => [
                'fieldType' => 'hidden',
                'label' => 'País Code',
                'validation' => ['nullable', 'min:2'],
            ],
            'db' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'type_login' => [
            'html' => [
                'fieldType' => null,
                //'validation' => ['nullable'],
                'options' => ['google', 'facebook', 'github', 'twitter'],
                'label' => 'Tipo de login',
                'user_level_admin' => true,
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
                'options' => 'db.users',
            ],
            'db' => [
                'type' => 'foreignId',
                //'nullable'=>true,
                'constrained' => true,
            ],
        ],
        'description' => [
            'html' => [
                'fieldType' => 'longText',
                'label' => 'Sua Descrição resumida',
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
        'created_at' => [
            'html' => [
                'fieldType' => null,
                'validation' => ['nullable', 'date'],
                'label' => 'Data da Criação',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],

        ],
        'updated_at' => [
            'html' => [
                'fieldType' => null,
                'validation' => ['nullable', 'date'],
                'label' => 'Data da Atualização',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'timestamp',
                'nullable' => true,
            ],

        ]
    ];

    // public function findOrFail($var)
    // {
    //     return $this->Model
    //         ->where('id', $var)
    //         ->orWhere('cpf', $var)
    //         ->firstOrFail();
    // }

    public function users_level()
    {
        return $this->belongsTo(UsersLevel::class, 'users_level_id')->select(['id', 'name']);
    }

    public function user_cnae()
    {
        return $this->belongsTo(UserCnae::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id'); //->select(['id', 'name']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');//->select(['id', 'name']);
    }

    public function scopeIncludeTablesRelations($query, array $add = [])
    {
        $myWhitRelations = [];

        // se não houver uma add por vias da função , pega a public var
        if (!count($add)) {
            $add = $this->addIncludeTablesRelations;
        }

        // add relations
        foreach ($this->campos as $kCampos => $vCampos) {

            if (str_contains($kCampos, '_id')) {
                $kCampos = (explode('_id', $kCampos))[0];

                switch ($kCampos) {

                    case 'tcdt_id':
                        $myWhitRelations[] = 'tennis_court_description_table';
                        break;
                    case 'tcit_id':
                        $myWhitRelations[] = 'tennis_court_involvement_table';
                        break;
                    // aqui foi necessário essa excessão por conto do final ID
                    case 'end_to_end_id':
                    case 'end_to_end':
                        break;

                    default:
                        $myWhitRelations[] = $kCampos;
                        //$myWhitRelations[] = Str::plural($kCampos);
                        break;
                }
            }
        }        
        $query->with(array_merge($myWhitRelations, $add));
        return $query;
    }
}
