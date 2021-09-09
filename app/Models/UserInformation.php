<?php


namespace App\Models;


use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserInformation extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'image_photo','birthday', 'age','sex','nationality','experience','self-introduction','membership_status','company_id','phone_number','area_code','link_youtube', 'introduction_from_admin','certification', 'link_zoom'
    ];
}
