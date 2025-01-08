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

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
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
