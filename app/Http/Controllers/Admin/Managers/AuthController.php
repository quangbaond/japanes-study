<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use DB;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show page login admin.
     * @author vinhppvk
     *
     * @return View
     */
    public function index()
    {
        return view('admin.managers.login');
    }

    /**
     * Handle login for admin.
     * * @author vinhppvk
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->has('remember_me') ? true : false;

        if (Auth::attempt($credentials , $rememberMe)  && (Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin') ) && Auth::user()->deleted_at == null &&  Auth::user()->auth == 1) {
            if ($rememberMe) {
                Cookie::queue('email', $request->email, 100000);
                Cookie::queue('password', $request->password, 100000);
                Cookie::queue('remember_me', 'checked', 100000);
            } else {
                Cookie::queue(Cookie::forget('email'));
                Cookie::queue(Cookie::forget('password'));
                Cookie::queue(Cookie::forget('remember_me'));
            }
            DB::table('users')->where('id', Auth::user()->id)->update(['last_login_at'  => now()]);

            // Show timezone when login
            $ip = geoip()->getLocation(geoip()->getClientIP());
            request()->session()->flash('success', __('validation_custom.M072_01') . $ip['timezone'] . __('validation_custom.M072_02'));

            return Auth::user()->role == config('constants.role.admin') ? redirect()->route('admin.admin-list') : redirect()->route('admin.teacher.index');
        }
        return redirect()->route('login')->with('error', 'ログイン情報が正しくありません。')->withInput();
    }

    /**
     * Handle logout for admin.
     * @author vinhppvk
     *
     * @return RedirectResponse
     */
    public function logout() {
        // Delete cache
        Cache::pull('user-is-online-' . Auth::id());

        Auth::logout();
        Session::flush();
        return Redirect('admin/login');

    }
}
