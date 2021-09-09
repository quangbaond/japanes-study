<?php
namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminHeaderComposer
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
        $user = DB::table('users')
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.id', Auth::id())
            ->first();
        $view->with('user', $user);
    }
}
