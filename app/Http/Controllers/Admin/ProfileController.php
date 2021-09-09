<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\ProfileRepository;
use App\Http\Requests\Admin\ProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProfileController extends Controller
{
    protected $profileRepository;

    /**
     * Display a listing of the resource.
     *
     * @param ProfileRepository $profileRepository
     */
    function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * profile
     * @return View
     */
    public function profile()
    {
        $profile = $this->profileRepository->profile();
        return view ('admin.profiles.profile', compact('profile'));
    }

    /**
     * Change Password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'password_new' => 'required|min:6',
                'password_confirm' => 'required|same:password_new|min:6',
                'password_old' => ['required', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        return $fail(__('profile.the_old_password_is_incorrect'));
                    }
                }]
            ],
            [

            ]
        );
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        }

        if ($this->profileRepository->changePassword($data)) {
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }

    /**
     * Update profile
     *
     * @param ProfileRequest $request
     * @return RedirectResponse
     */
    public function updateProfile(ProfileRequest $request)
    {
        $data = $request->all();
        if ( $this->profileRepository->updateProfile($data) ) {
            return redirect()->route('profile')->with('success', __('notification.update-success'));
        } else {
            return redirect()->route('profile')->with('error', __('notification.update-error'));
        }
    }
}
