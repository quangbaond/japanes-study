<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    public $table = 'notifications';
    protected $fillable = ['title' , 'content' , 'receiver_class' , 'start_date' , 'end_date' , 'created_by'];
}
