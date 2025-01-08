<?php

namespace App\Models;

use App\Models\NossaModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersLevel extends NossaModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

}
