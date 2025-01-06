<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TennisCourtType extends NossaModel
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
       'user_id',
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
        'name'=>[
            'html'=>[
                'fieldType'=>'text',
                'label'=>'Tipo de Quadra (ex.: Urbana Coberta)',
                'validation'=> ['required','max:255','unique:tennis_court_types,name'],
                'placeholder'=> 'Tipo de Quadra (ex.: Urbana Coberta)',
            ],
            'db'=>[
                'type'=>'string',
                'nullable'=>true,
                'unique'=>true,
            ],
        ],
        'user_id'=>[
            'html'=>[
                'fieldType'=>'hidden',
                'label'=> 'Responsável',
                'options'=> 'db.users',
            ],
            'db'=>[
                'type'=>'foreignId',
                'nullable'=>true,
                'constrained'=>true,
            ],
        ],
    ];
}
