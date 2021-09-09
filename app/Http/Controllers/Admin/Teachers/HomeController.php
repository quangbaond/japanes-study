<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Repositories\Admin\Managers\TeacherRepository;
use App\Repositories\Admin\PusherRepository;
use App\Rules\CheckEmailRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\ProfileRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Pusher\Pusher;
use Validator;
use App\Helpers\Helper;
use App\Rules\CheckDateRule;

class HomeController extends Controller
{
    private $profileRepo, $teacherRepository, $pusherRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProfileRepository $profileRepo, TeacherRepository $teacherRepository, PusherRepository $pusherRepository)
    {
        $this->profileRepo = $profileRepo;
        $this->teacherRepository = $teacherRepository;
        $this->pusherRepository = $pusherRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route('teacher.my-page');
    }
        /**
     * Display teacher.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */


        /**
     * Display teacher's profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile() {
        $profile = $this->profileRepo->profile();
        $course = $this->profileRepo->getAllCourse();
        return view('admin.teachers.edit',['profile' => $profile, 'nationality' => config('nation'), 'phoneNumber' => config('phone_number'),'course' => $course]);
    }

    /**
     * @param Request $request
     */
    public function updateProfile(Request $request) {
        $input = $request->all();
        $area_code=[];
        $sex=[];
        $nation_code=[];


        foreach (config('phone_number') as $key => $value) {
            array_push($area_code, ($key . '-' . $value['code']));
        }

        foreach (config('constants.sex.id') as $key => $value) {
            array_push($sex, $value);
        }

        foreach (config('nation') as $key => $value) {
            array_push($nation_code, $key);
        }


        $area_code = implode(',',$area_code);
        $sex = implode(',',$sex);
        $nation_code = implode(',',$nation_code);

        $rules = [];
        $year = $input['year'];
        $month = $input['month'];
        $day = $input['day'];
        $input['birthday']= null;

        if(!empty($year) && !empty($month) && !empty($day)) {
            $input['birthday'] = $year."-".$month. "-" . $day;
            $rules['birthday'] = [new CheckDateRule()];
        }
        if( (empty($year)) ) {
            $rules['birthday'] = 'required';
            $rules['year'] = 'required';
        }
        if( empty($month) ) {
            $rules['birthday'] = 'required';
            $rules['month'] = 'required';
        }
        if(  empty($day)  ) {
            $rules['birthday'] = 'required';
            $rules['day'] = 'required';
        }
        if(empty($year) && empty($month) && empty($day)) {
            $rules['birthday'] = '';
            $rules['day'] = '';
            $rules['month'] = '';
            $rules['year'] = '';
        }

        Validator::extend('german_url', function($attribute, $value, $parameters)  {
            $url = str_replace(["ä","ö","ü"], ["ae", "oe", "ue"], $value);
            return filter_var($url, FILTER_VALIDATE_URL);
        });

        $rules['link_zoom'] = ['required', 'german_url', function ($attribute, $input, $fail) {
            if (!strstr($input, 'http') || !strstr($input, '?pwd=') || !strstr($input, 'zoom.us')) {
                return $fail(__('validation_custom.M066'));
            }
        }];
        $rules['nickname'] = 'required|max:50';
        isset($input['sex'])  ? $rules['sex'] = 'numeric|in:'.$sex : $rules['sex'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'numeric|digits_between:1,11' : $rules['phone_number'] = '';
        $input['area_code'] != '' ? $rules['area_code'] = 'in:'. $area_code : $rules['area_code'] = '';
        $input['nationality'] != '' ? $rules['nationality'] = 'in:'.$nation_code : $rules['nationality'] = '';
        isset($input['image_photo']) ? $rules['image_photo'] = 'mimes:jpeg,jpg,png,gif|max:5120' : $rules['image_photo'] = '';
        $input['experience'] != '' ? $rules['experience'] = 'max:100' : $rules['experience'] = '';
        $input['certification'] != '' ? $rules['certification'] = 'max:100' : $rules['certification'] = '';
        $input['self-introduction'] != '' ? $rules['self-introduction'] = 'max:500' : $rules['self-introduction'] = '';

        $attributes = [
            'link_zoom' => 'ズームリンク',
            'nickname' => 'ニックネーム',
            'year' => '年',
            'month' => '月',
            'day' => '日',
            'sex' => '性別',
            'phone_number' => '電話番号',
            'nationality' => '国籍',
            'experience' => '講師歴',
            'certification' => '資格',
            'self-introduction' => '自己紹介',
            'image_photo' => '画像',
            'birthday' => '生年月日'
        ];


        $message = [
            'link_zoom.required'                => __('validation_custom.M001',['attribute'=>':attribute']),
            'link_zoom.german_url'                   => __('validation_custom.M066'),
            'nickname.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'nickname.max'                      => __('validation_custom.M003',['attribute'=>':attribute','min' => '1','max' => '50']),
            'birthday.date_format'              => __('validation_custom.M020'),
            'sex.numeric'                       => __('validation_custom.M006',['attribute'=>':attribute']),
            'phone_number.numeric'              => __('validation_custom.M006',['attribute'=>':attribute']),
            'area_code.in'                      => ':attribute '.config('validation.digits_between'),       //chua co
            'image_photo.max'                   => __('validation_custom.M018',['attribute'=>':attribute','max' => '5120kb']),
            'phone_number.between'              => __('validation_custom.M018',['attribute'=>':attribute']),
            'phone_number.digits_between'       => __('validation_custom.M018',['attribute'=>':attribute','max' => '11']),
            'birthday.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'year.required'                     => __('validation_custom.M001',['attribute'=>':attribute']),
            'month.required'                    => __('validation_custom.M001',['attribute'=>':attribute']),
            'day.required'                      => __('validation_custom.M001',['attribute'=>':attribute']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {

            //update profile
            $status = $this->profileRepo->updateProfile($input);
            if($status) {
                return $this->responseSuccess($status,__('validation_custom.M027'));
            }
            else {
                return $this->responseSuccess(null,__('validation_custom.M028'));
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $data = $request->all();

        $attributes = [
            'old_password' => '現在のパスワード',
            'new_password' => '新しいパスワード',
            'new_password_confirmation' => '新しいパスワード（確認）'
        ];

        $validator = Validator::make(
            $data,
            [
                'new_password' => 'required|between:8,16',
                'new_password_confirmation' => 'required|same:new_password',
                'old_password' => ['required', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        return $fail(__('validation_custom.M035'));
                    }
                }]
            ],
            [
                'new_password.required'             => __('validation_custom.M001',['attribute'=>':attribute']),
                'new_password.between'              => __('validation_custom.M003',['attribute'=>':attribute','min'=>'8','max'=>'16']),
                'new_password_confirmation.required'=>  __('validation_custom.M001',['attribute'=>':attribute']),
                'new_password_confirmation.same'    => __('validation_custom.M025'),
                'old_password.required'             => __('validation_custom.M001',['attribute'=>':attribute'])
            ]
        )->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        }

        if ($this->profileRepo->changePassword($data)) {
            return $this->responseSuccess(null, __('validation_custom.M059'));
        } else {
            return $this->responseError();
        }
    }

    /**
     * validate email by ThachDang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeEmail(Request $request) {
        $input = $request->all();

        $rules = [];
        $rules['old_email'] = ['required', 'email', 'between:3,70', new CheckEmailRule(),'in:'.Auth::user()->email];
        $rules['new_email'] = ['required', 'email', 'between:3,70', new CheckEmailRule(),'unique:users,email,'.$input['new_email'] ];
        $rules['new_email_confirmation'] = 'required|same:new_email';


        $attributes = [
            'old_email' => '現在のメールアドレス',
            'new_email' => '新しいメールアドレス',
            'new_email_confirmation' => '新しいメールアドレス（確認)'
        ];


        $message = [
            'old_email.required'            => __('validation_custom.M001',['attribute'=>':attribute']),
            'old_email.in'                  => 'This email is incorrect',
            'old_email.email'               => __('validation_custom.M002'),
            'old_email.between'             => __('validation_custom.M003',['attribute'=>':attribute']),
            'old_email.regex'               => ':attribute'.config('validation.regex_alphanumeric'),
            'new_email.required'            => __('validation_custom.M001',['attribute'=>':attribute']),
            'new_email.email'               => __('validation_custom.M002'),
            'new_email.between'             => __('validation_custom.M003',['attribute'=>':attribute']),
            'new_email.regex'               => ':attribute'.config('validation.regex_alphanumeric'),
            'new_email_confirmation.same'   =>  __('validation_custom.M026'),
            'new_email_confirmation.required'   => __('validation_custom.M001',['attribute'=>':attribute']),
            'new_email.unique'                  => __('validation_custom.M023')
        ];


        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            $this->profileRepo->changeEmail($input);
            return $this->responseSuccess();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCourse(Request $request) {
        $input = $request->all();
        DB::beginTransaction();
        try {

            DB::table('course_can_teach')->where('course_id',$input['id'])->where('teacher_id',Auth::user()->id)->delete();
            DB::commit();
            return $this->responseSuccess();

        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseError(null,$th);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCourse(Request $request) {
        $input = $request->all();
        $rules = [];
        $rules['id'] = 'required|numeric';

        $attributes = [
            'id' => '対応可能コース',
        ];

        $message = [
            'id.required'    => __('validation_custom.M001',['attribute'=>':attribute']),
            'id.numeric'     => __('validation_custom.M006',['attribute'=>':attribute']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {

            try {
                DB::table('course_can_teach')->insert([
                    'course_id' => $input['id'],
                    'teacher_id' => Auth::user()->id
                ]);
                return $this->responseSuccess();
            } catch (\Throwable $th) {
                dd($th);
            }

        }
    }

    /**
     * validate youtube link by ThachDang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateLinkYoutube(Request $request) {
        $input = $request->all();
        $rules['link_youtube'] = ['required',function ($attribute, $input, $fail) {
            if (!preg_match('/https:\/\/www\.youtube\.com\/watch\?v=[^&]+/', $input)) {
                return $fail(__('validation_custom.M034'));
            }
        }];
        $attributes = [
            'link_youtube' => 'YouTubeリンク',
        ];

        $message = [
            'link_youtube.required'    => __('validation_custom.M001',['attribute'=>':attribute']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

    /**
     * send the notification to teacher when click start meeting room by Teacher
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Pusher\PusherException
     */
    public function startMeetingRoomWithStudent(Request $request) {
        $input = $request->all();
        $zoom_link = $this->teacherRepository->addScheduleWithStudent($request);
        $data = [
            'data' => $zoom_link,
            'type' => 1, //send link to student
        ];

        $channel = 'notification-open-lesson-student';
        $status = $this->pusherRepository->sendMessageWhenTeacherCancel($channel, $input['student_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCancelToStudent(Request $request) {
        $input = $request->all();
        $isSuddenLessonOrBooked = $this->teacherRepository->isSuddenOrBookedToCancel($input);
        if(is_null($isSuddenLessonOrBooked)) {
            $status_schedule = config('constants.teacher_schedule.free_time');
            $before_status = config('constants.teacher_schedule.booking');
            $this->teacherRepository->changeStatusOfTeacher($input, $status_schedule, $before_status);
        }
        $data = [
            'data' => null,
            'type' => 2, //the teacher is canceled
        ];

        $channel = 'notification-open-lesson-student';
        $status = $this->pusherRepository->sendMessageWhenTeacherCancel($channel, $input['student_id'], $data);
        return $status ? $this->responseSuccess($data) : $this->responseError();
    }
    public function notification() {
        return view('admin.teachers.notification');
    }

    /**
     * get student curriculum
     * @return View
     */
    public function indexCourse() {
        $courses = $this->teacherRepository->getCourses();
        return view('admin.teachers.courses.index', compact('courses'));
    }

    /**
     * get course detail
     * @param $id
     * @return View
     */
    public function detailCourse($id) {
        $course = $this->teacherRepository->getCourseById($id);
        if($course == null) {
            abort(404);
        }
        $lessons = $this->teacherRepository->getLessonsOfCoursesByCourseId($id);
        return view('admin.teachers.courses.detail', compact('course', 'lessons'));
    }

}
