<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class NossaModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $addIncludeTablesRelations = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = array();

    /**
     * Variavel com os campos do BD
     *
     * ex. validation     'title' => 'required|unique:posts|max:255',
     *
     * @var array
     */
    public $campos = array();

    /* function __construct()
    {
        $this->campos = array_merge(
            $this->campos,
            [
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
            ]
        );

        parent::boot();

    } */

    /*
    pegar o user logged

     */
    public function userLogged()
    {
        return Auth::user()->id;
    }

    /**
     * criar slug
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /* public static function createSlug($var)
    {
        return SlugService::createSlug(Blog::class, 'slug', $var);

    } */

    public function camposMasterOptions()
    {
        $r = $this->orderBy('name', 'DESC')->get(['id', 'name'])->toArray();
        $this->campos['master']['options'] = $r;
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }


    /**
     * relations
     */

     public function payment_method()
     {
         return $this->belongsTo(PaymentMethod::class);
     }

     public function discount_policy()
     {
         return $this->belongsTo(DiscountPolicy::class);
     }

     public function customer_request()
     {
         return $this->belongsTo(CustomerRequest::class);
     }
     
     public function customer_request_all_relations()
     {
         return $this->belongsTo(CustomerRequest::class, 'customer_request_id')->includeTablesRelations();
     }
     
     public function tennis_court_opening_hour()
     {
         return $this->belongsTo(TennisCourtOpeningHour::class);
     }

     public function product()
     {
         return $this->belongsTo(Product::class);
     }
     
     public function tennis_court_involvement_table()
     {
         return $this->belongsTo(TennisCourtInvolvementTable::class)->with('icon');
     }

     public function products_default()
     {
         return $this->belongsTo(ProductsDefault::class)->with('product_department');
     }

    public function product_department()
    {
        return $this->belongsTo(ProductDepartment::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id'); //->select(['id', 'name']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');//->select(['id', 'name']);
    }

    public function cnae()
    {
        return $this->belongsTo(Cnae::class, 'cnae_id');
    }

    public function config_sistem()
    {
        return $this->belongsTo(ConfigSistem::class, 'config_sistem_id');
    }

    public function icon()
    {
        return $this->belongsTo(Icons::class, 'icon_id')->select(['id', 'name']);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function slug()
    {
        return $this->belongsTo(Slug::class, 'slug_id');
    }

    public function tennis_court()
    {
        return $this->belongsTo(TennisCourt::class, 'tennis_court_id');
    }

    public function tennis_court_calendar()
    {
        return $this->belongsTo(TennisCourtCalendar::class)->with('tennis_court_opening_hour');
    }

    public function tennis_court_calendar_between_dates()
    {
        return $this->belongsTo(TennisCourtCalendar::class);
    }

    public function tennis_court_description()
    {
        return $this->belongsTo(TennisCourtDescription::class);
    }

    public function tennis_court_description_table()
    {
        return $this->belongsTo(TennisCourtDescriptionTable::class)
            //->select(['name', 'id', 'unit','score'])
            ->with('icon');
    }

    public function tennis_court_group() 
    {
        return $this->belongsTo(TennisCourtGroup::class)
            ->with('tennis_court_type');
    }

    public function tennis_court_type()
    {
        return $this->belongsTo(TennisCourtType::class)
            ->select(['name', 'id']);
    }

    public function tennis_court_media()
    {
        return $this->belongsTo(TennisCourtMedia::class);
    }

    public function user_cnae()
    {
        return $this->belongsTo(UserCnae::class, 'user_cnae_id');
    }

    public function user_level_role()
    {
        return $this->belongsTo(UserLevelRoles::class, 'user_level_role_id');
    }

    public function users_level()
    {
        return $this->belongsTo(UsersLevel::class, 'users_level_id')->select(['id', 'name']);
    }

    public static function scopeDataWithinDistance($query, $latitude, $longitude, $distance)
    {
        return $query->selectRaw('*, 
            ( 6371 * acos( cos( radians(?) ) *
            cos( radians( lat ) )
            * cos( radians( lng ) - radians(?)
            ) + sin( radians(?) ) *
            sin( radians( lat ) ) )
            ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<', $distance)
            /* ->whereIn('id', static function ($select) {
                $select
                     ->from('tennis_courts')
                     ->selectRaw('MIN(id)')
                     ->groupBy(['tennis_courts.city'])
                     ;
           }) */
            ->orderBy('distance')
            //->get()
        ;

        // Latitude and longitude of the center point
        // $latitude = 40.7128; // New York City latitude
        // $longitude = -74.0060; // New York City longitude

        // // Distance in kilometers
        // $distance = 10; // 10 kilometers

        // // Fetch data within the specified distance
        // $dataWithinDistance = Location::getDataWithinDistance($latitude, $longitude, $distance);

        // // Output the fetched data
        // foreach ($dataWithinDistance as $location) {
        //     echo "Name: " . $location->name . ", Distance: " . $location->distance . " kilometers\n";
        // }
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
