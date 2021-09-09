<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\StudentRepository;
use App\Rules\CheckEmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SettingPasswordController extends Controller
{
    protected $studentRepository;

    /**
     * Create a new controller instance.
     * @param StudentRepository $studentRepository
     */
    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Setting password blade
     * @author vinhppvk
     *
     * @return mixed
     */
    public function settingPassword()
    {
        return view('admin.students.info');
    }

    /**
     * Setting password
     * @author vinhppvk
     *
     * @param Request $request
     * @return mixed
     */
    public function updatePassword(Request $request){
        $rules['password'] = 'required|between:8,16';
        $rules['password_confirmation'] = 'required|between:8,16|same:password';
        $message['password.confirmed'] = __('validation_custom.M025');
        $message['password_confirmation.same'] = __('validation_custom.M025');

        if(empty(Auth::user()->email)){
            $rules['email'] = ['required','email','between:3,70', new CheckEmailRule(),'unique:users'];
            $message['email.unique'] = __('validation_custom.M021');
            $message['email.regex'] = __('validation_custom.M004');
        }
        $validator = Validator::make($request->all(),$rules,  $message);
        if ($validator->fails()) {
            return Redirect::back()->with('error', __('login.update_error'))->withErrors($validator)->withInput();
        }
        $check = $this->studentRepository->updateInfo($request->all());
        if($check == true){
            return redirect(route('student-dashboard'));
        } else {
            return Redirect::back()->with('error', __('login.update_error'));
        }
    }

}
