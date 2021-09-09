<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lesson extends Model
{
    protected $fillable = [
        'name','description','time_learn','course_id'
    ];
}
