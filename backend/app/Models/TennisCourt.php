<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourt extends NossaModel
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
        'tennis_court_group_id',
        'registration_phase',

        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'geo_point'
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
        'tennis_court_group_id' => [
            'html' => [
                'fieldType' => 'select',
                'options' => 'db.tennis_court_groups',
                'validation' => ['nullable'],
                'label' => 'Grupo do Item',
                'user_level_admin' => true,
            ],
            'db' => [
                'type' => 'foreignId',
                'nullable' => true,
                'constrained' => true,
                'default' => 1,
            ],

        ],
    ];

    // para determinar a fase onde se encontra o cadastro
    public $registration_phases = [
        // 'TennisCourt',
        // 'Product',
        // 'TennisCourtDescription',
        // 'TennisCourtMedia',
        // 'TennisCourtOpeningHour',
        'dashboardProductCreate',
        'dashboardTennisCourtOpeningHourCreate',
        'dashboardTennisCourtDescritpionCreate',
        'dashboardTennisCourtMediaCreate',
        'full'
    ];

    function __construct()
    {
        $user = new User();
        $userCampos = $user->campos;
        unset(
            $userCampos['password']
        );
        $this->campos = array_merge($this->campos, $userCampos);

        parent::__construct();
    }

    public function tennis_court_opening_hour()
    {
        return $this->hasMany(TennisCourtOpeningHour::class);
    }

    public function tennis_court_calendar()
    {
        return $this->hasMany(TennisCourtCalendar::class);
    }

    public function tennis_court_description()
    {
        return $this->hasMany(TennisCourtDescription::class)
            ->with('tennis_court_description_table');
    }

    function tennisCourtMedia()
    {
        return $this->hasMany(TennisCourtMedia::class, 'tennis_court_id', 'id')->orderBy('order');
    }

    public function tennis_court_media()
    {
        return $this->hasMany(TennisCourtMedia::class);
    }

    public function tennis_court_involvement()
    {
        return $this->hasMany(TennisCourtInvolvement::class)
            //->where('user_id', auth('sanctum')->user()->id)
            ->with('tennis_court_involvement_table');
    }
}
