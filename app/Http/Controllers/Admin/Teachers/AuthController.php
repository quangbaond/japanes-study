<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\LoginRequest;
use App\Repositories\Admin\Managers\ProfileRepository;
use App\Services\MailService;
use Illuminate\View\View;
use DB;

class AuthController extends Controller
{
    protected $mailServices, $profileRepo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        MailService $mailServices,
        ProfileRepository $profileRepo
    )
    {
        $this->mailServices = $mailServices;
        $this->profileRepo =$profileRepo;
    }

    /**
     * Show page login teacher.
     * @return View
     */
    public function index()
    {
        return view('admin.teachers.login');
    }

    /**
     * Handle login for teacher.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->has('remember_me') ? true : false;

        if (Auth::attempt($credentials , $rememberMe)  && Auth::user()->role == config('constants.role.teacher') && Auth::user()->deleted_at == null && Auth::user()->auth == 1) {
            if ($rememberMe) {
                Cookie::queue('email_teacher', $request->email, 100000);
                Cookie::queue('password_teacher', $request->password, 100000);
                Cookie::queue('remember_me_teacher', 'checked', 100000);
            } else {
                Cookie::queue(Cookie::forget('email_teacher'));
                Cookie::queue(Cookie::forget('password_teacher'));
                Cookie::queue(Cookie::forget('remember_me_teacher'));
            }
            App::setLocale('ja');
            DB::table('users')->where('id', Auth::user()->id)->update(['last_login_at'  => now()]);

            // Show timezone when login
            $ip = geoip()->getLocation(geoip()->getClientIP());
            request()->session()->flash('success', __('validation_custom.M072_01') . $ip['timezone'] . __('validation_custom.M072_02'));

            return redirect()->intended('teacher/my-page');
        }
        return redirect()->route('login.teacher')->with('error', 'ログイン情報が正しくありません。')->withInput();
    }

    /**
     * Handle logout for teacher.
     * @author vinhppvk
     *
     * @return RedirectResponse
     */
    public function logout() {
        // Delete cache
        Cache::pull('user-is-online-' . Auth::id());

        Session::flush();

        //update last_seen
        DB::table('users')
            ->where('id', '=', Auth::user()->id)
            ->update([
                'last_seen' => null
            ]);
        Auth::logout();
        return Redirect('teacher/login');
    }

    public function activateUser($new_email, $token)
    {

        $data = $this->mailServices->activateUser($token);
        if ($data['status']) {
            $user = $this->profileRepo->updateEmail($new_email, $data['user_id']);
            return view('mails.mail-change-successfully');
        } else {
            if ($data['flag'] == 'expire') {
                return view('admin.teachers.expire');
            } else {
                abort(404);
            }
        }
    }
}

