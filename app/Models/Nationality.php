<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    public $table = 'nationality';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
