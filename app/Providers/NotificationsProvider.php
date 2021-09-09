<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifications;
use App\Models\Receiver;
use Illuminate\Support\Str;
use Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Http\Controllers\TimezoneController;
class NotificationsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer('*',function($view){
            $view->with('panelNotificationStudent', $this->panelNotificationUser(config('constants.role.student')));
            $view->with('getNotificationStudent', $this->getNotificationByUser());
            $view->with('panelNotificationTeacher', $this->panelNotificationUser(config('constants.role.teacher')));
            $view->with('getNotificationTeacher', $this->getNotificationByUser());
            $view->with('getNotificationAdmin', $this->getNotificationByUser());

        });
    }

    /**
     * panel notification.
     * @author quangbaorp
     *
     * @param $receiverClass
     * @return Collection
     */
    public static function panelNotificationUser($receiverClass)
    {
        return Notifications::orWhere('receiver_class' , 1)
            ->orWhere('receiver_class' , $receiverClass)
            ->orderBy('id', 'DESC')
            ->get()
            ->filter(function ($value){
                $value->start_date = Timezone::convertToLocal(Carbon::parse($value->start_date) , "Y-m-d");
                $value->end_date = Timezone::convertToLocal(Carbon::parse($value->end_date) , "Y-m-d");
                $value->title = Str::limit($value->title , 60);
                $value->content = Str::limit($value->content , 120);
                return $value;
            })
            ->where('start_date' , '<=' , Timezone::convertToLocal(Carbon::now() , "Y-m-d"))
            ->where('end_date' , '>=' , Timezone::convertToLocal(Carbon::now() , "Y-m-d"))
            ->all();
    }
    /**
    * notification User
    * @author quangbaorp
    *
    * @return array
     */
    public static function getNotificationByUser()
    {
        if (Auth::check()) {
            $user = Auth::user()->id;
            $notifications['notifications'] = Notifications::leftJoin('receiver' , 'notifications.id' , '=' , 'receiver.notification_id')
                ->orwhere('receiver.user_id' , $user)
                ->orderBy('created_at' , 'desc')
                ->limit(10)
                ->select('receiver.*','notifications.*')
                ->get()
                ->filter(function($value) {
                    $value->title = Str::limit($value->title , 20);
                    return $value;
                })
                ->all();
            $notifications['num_read_at']  =
            Notifications::leftJoin('receiver' , 'notifications.id' , '=' , 'receiver.notification_id')
            ->orwhere('receiver.user_id' , $user)
            ->whereNull('read_at')
            ->get()->count();

            //dd($nofications);
            return $notifications;
        }
    }
}

