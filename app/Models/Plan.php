<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'product_id',
        'stripe_plan',
        'interval',
        'interval',
        'interval_count',
        'cost',
        'description'
    ];
}
