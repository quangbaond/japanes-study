<?php
namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentHeaderComposer
{


    /**
     * Create a header composer.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = DB::table('users')->select('users.id', 'user_information.membership_status', 'user_payment_info.id as user_payment_info_id' , 'user_information.image_photo')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->leftJoin('user_payment_info', 'user_payment_info.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->first();
        $view->with('user', $user);
    }
}
