<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Admin\Managers\UserRepository;
use App\Repositories\Admin\Managers\ProfileRepository;
use App\Repositories\Admin\Managers\StudentRepository;
use App\Repositories\Admin\Managers\TeacherRepository;
use App\Rules\CheckDateRule;
use Carbon\Carbon;
use Timezone;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Nationality;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\TeacherRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TimezoneController;

class TeacherController extends Controller
{
    protected $userRepository;
    protected $studentRepository;
    protected $profileRepository;
    protected $teacherRepository;
    protected $timezone;

    /**
     * Display a listing of the resource.
     *
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @param StudentRepository $studentRepository
     * @param TeacherRepository $teacherRepository
     * @param TimezoneController $timezone
     */
    function __construct(UserRepository $userRepository, ProfileRepository $profileRepository, StudentRepository $studentRepository , TeacherRepository $teacherRepository, TimezoneController $timezone)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->studentRepository =  $studentRepository;
        $this->teacherRepository = $teacherRepository;
        $this->timezone = $timezone;
    }

    /**
     * Display teacher.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.managers.teachers.index', ['nationalities' => config('nation')]);
    }

    /**
     * Validation search list teachers.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function teacherListValidation(Request $request)
    {
        // Data request
        $input = $request->all();

        // Rule validation
        $rules = [];
        $input['teacher_id'] != '' ? $rules['teacher_id'] = 'integer' : $rules['teacher_id'] = '';
        $input['age_from'] != '' ? $rules['age_from'] = 'numeric' : $rules['age_from'] = '';
        $input['age_to'] != '' ? $rules['age_to'] = 'numeric' : $rules['age_to'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'numeric|digits_between:1,11' : $rules['phone_number'] = '';
        ($input['created_at_from'] != '' && $input['created_at_to'] != '') ? $rules['created_at_to'] = 'after_or_equal:created_at_from' : $rules['created_at_to'] = '';

        if($input['age_to'] != '' && $input['age_from'] && (int)$input['age_from'] > (int)$input['age_to']){
            $rules['age_from'] = '|before:age_to';
        }
        // Set name for field
        $attributes = array(
            'teacher_id'    => '講師ID',
            'phone_number'  => '電話番号',
            'age_from'      => '年齢',
            'age_to'        => '年齢',
        );

        // Message validation
        $message = [
            'teacher_id.integer'            => ':attribute'.config('validation.integer'),
            'phone_number.numeric'          => ':attribute'.config('validation.numeric'),
            'phone_number.digits_between'   => ':attribute'.config('validation.digits_between'),
            'age_from.numeric'              => ':attribute'.config('validation.numeric'),
            'age_from.before'               => config('validation.before'),
            'age_to.numeric'                => ':attribute'.config('validation.numeric'),
            'created_at_to.after_or_equal'  => config('validation.after_or_equal'),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

    /**
     * Data teachers list
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function teachersDataTable(Request $request)
    {
        $teachers = $this->userRepository->teacherDataTable();
        return Datatables::of($teachers)
            ->addColumn('td_hidden', function ($teacher) {
                return '<td></td>';
            })
            ->addColumn('action', function ($teacher) {
                return '<a href="'. route('admin.teacher.detail', $teacher->id) .'" class="btn btn-sm btn-primary btn-flat">詳細</a>';
            })
            ->addColumn('created_at_user', function ($teacher) {
//                return Timezone::convertToLocal(Carbon::parse($teacher->created_at_user), 'Y-m-d H:i:s');
                return Helper::formatDate(Timezone::convertToLocal(Carbon::parse($teacher->created_at_user), 'Y-m-d H:i:s'));
            })
            ->addColumn('checkbox', function ($teacher) {
                return '<input type="checkbox" class="chk_item" value="' .$teacher->id. '" name="teacher_id" />';
            })
            ->addColumn('sex', function ($teacher) {
                if (empty($teacher->sex)) {
                    return config('constants.no_data');
                } else {
                    if ($teacher->sex == config('constants.sex.id.male')) {
                        return config('constants.sex.name.male');
                    } elseif ($teacher->sex == config('constants.sex.id.female')) {
                        return config('constants.sex.name.female');
                    } elseif ($teacher->sex == config('constants.sex.id.unspecified')) {
                        return config('constants.sex.name.unspecified');
                    } else {
                        return config('constants.no_data');
                    }
                }
            })
            ->rawColumns(['td_hidden', 'action','created_at','checkbox', 'sex'])
            ->make(true);
    }

    /**
     * Create teacher.
     *
     * @return View
     */
    public function create()
    {
        $company = DB::table('company')->get();
        $course = $this->teacherRepository->getAllCourses();
        return view('admin.managers.teachers.create',['company' => $company,'nationalities' => config('nation'),'phoneNumber' => config('phone_number') , 'courses' => $course]);
    }

    /**
     * Create teacher form.
     *
     * @param TeacherRequest $request
     * @return RedirectResponse
     */
    public function addTeacher(TeacherRequest $request){
        $input = $request->all();
        $check = $this->teacherRepository->createTeacher($input);
        if ($check['status']) {
            return redirect(route('admin.teacher.index'))->with('success',$check['message']);
        } else {
            return redirect(route('admin.teacher.create'))->withInput()->with('error_isset_email',$check['message']);
        }
    }


    protected function generateRandomString($length = 9) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    /**
     * Delete teacher.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTeacher(Request $request)
    {
        $input = $request->all();
        $check = $this->teacherRepository->deleteAll($input);
        if ($check) {
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }

    /**
     * Detail teacher
     * @param $id
     * @return View
     */
    public function detail($id)
    {
        $teacher = $this->teacherRepository->teacherDetail($id);
        $course = $this->teacherRepository->getAllCourseOfTeacherById($id);
        return view('admin.managers.teachers.detail' , ['teacher' => $teacher, 'nationality' => config('nation'), 'phoneNumber' => config('phone_number'),'course' => $course]);
    }

    /**
     * @param $id
     * @return View
     */
    public function bookingSubtitute($id)
    {
        $teacher = $this->teacherRepository->teacherDetail($id);
        $schedule = DB::table('teacher_schedule')->where('teacher_id' , $id)->get();
        $start_date = [];
        foreach($schedule as $item){
          array_push($start_date , $item->start_date);
        }
        $start_date= array_unique($start_date);
        return view('admin.managers.teachers.bookingSubstitute' , ['teacher' => $teacher , 'start_date' => $start_date , 'schedule' => $schedule]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function resetPassword($id) {
        $is_success = $this->teacherRepository->resetPasswordForTeacherById($id);
        if($is_success) {
            return $this->responseSuccess(null, __('validation_custom.M063'));
        }
        return $this->responseError();
    }

    public function updateProfile(Request $request, $id) {
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

        $rules['nickname'] = 'required|max:50';
        isset($input['sex'])  ? $rules['sex'] = 'numeric|in:'.$sex : $rules['sex'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'bail|numeric|digits_between:1,11' : $rules['phone_number'] = '';
        $input['area_code'] != '' ? $rules['area_code'] = 'in:'. $area_code : $rules['area_code'] = '';
        $input['nationality'] != '' ? $rules['nationality'] = 'in:'.$nation_code : $rules['nationality'] = '';
        $input['experience'] != '' ? $rules['experience'] = 'max:100' : $rules['experience'] = '';
        $input['certification'] != '' ? $rules['certification'] = 'max:100' : $rules['certification'] = '';
        $input['introduction_from_admin'] != '' ? $rules['introduction_from_admin'] = 'max:500' : $rules['introduction_from_admin'] = '';
        $rules['course'] = 'required';

        $attributes = [
            'nickname' => 'ニックネーム',
            'year' => '年',
            'month' => '月',
            'day' => '日',
            'sex' => '性別',
            'phone_number' => '電話番号',
            'nationality' => '国籍',
            'experience' => '講師歴',
            'certification' => '資格',
            'introduction_from_admin' => 'スタッフからの紹介',
            'birthday' => '生年月日',
            'course' => '対応可能コース'
        ];


        $message = [
            'nickname.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'nickname.max'                      => __('validation_custom.M003',['attribute'=>':attribute','min' => '1','max' => '50']),
            'birthday.date_format'              => __('validation_custom.M020'),
            'sex.numeric'                       => __('validation_custom.M006',['attribute'=>':attribute']),
            'phone_number.numeric'              => __('validation_custom.M006',['attribute'=>':attribute']),
            'area_code.in'                      => ':attribute '.config('validation.digits_between'),       //chua co
            'phone_number.between'              => __('validation_custom.M018',['attribute'=>':attribute']),
            'phone_number.digits_between'       => __('validation_custom.M018',['attribute'=>':attribute','max' => '11']),
            'birthday.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'year.required'                     => __('validation_custom.M001',['attribute'=>':attribute']),
            'month.required'                    => __('validation_custom.M001',['attribute'=>':attribute']),
            'day.required'                      => __('validation_custom.M001',['attribute'=>':attribute']),
            'course.required'                   => __('validation_custom.M001',['attribute'=>':attribute']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            //update profile
            $is_success = $this->teacherRepository->updateProfileForTeacherById($input, $id);
            if($is_success) {
                return $this->responseSuccess($is_success,__('validation_custom.M027'));
            }
            else {
                return $this->responseSuccess(null,__('validation_custom.M028'));
            }
        }
    }
}

