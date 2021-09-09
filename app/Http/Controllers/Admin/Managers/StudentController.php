<?php

namespace App\Http\Controllers\Admin\Managers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\Http\Controllers\TimezoneController;
use App\Http\Requests\Admin\StudentRequest;
use App\Repositories\Admin\Managers\UserRepository;
use App\Repositories\Admin\Managers\ProfileRepository;
use App\Repositories\Admin\Managers\StudentRepository;
use App\Rules\CheckDateRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TeacherRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Timezone;

class StudentController extends Controller
{
    protected $userRepository;
    protected $profileRepository;
    protected $studentRepository;

    /**
     * Display a listing of the resource.
     *
     * @param UserRepository $userRepository
     * @param ProfileRepository $profileRepository
     * @param StudentRepository $studentRepository
     */
    function __construct(
        UserRepository $userRepository,
        ProfileRepository $profileRepository,
        StudentRepository $studentRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display students.
     * @return View
     */
    public function index()
    {
        $students = DB::table('company')->get();
        return view('admin.managers.students.index', compact('students'));
    }

    /**
     * Validation students.
     * @param Request $request
     * @return JsonResponse
     */
    public function studentValidation(Request $request)
    {
        $input = $request->all();
        //Rule validation
        $rules = [];
        $input['student_id'] != '' ? $rules['student_id'] = 'integer' : $rules['student_id'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'numeric|digits_between:1,11' : $rules['phone_number'] = '';
        ($input['from_date'] != '' && $input['to_date'] != '') ? $rules['to_date'] = 'after_or_equal:from_date' : $rules['to_date'] = '';

        // Set name for field
        $attributes = array(
            'student_id'    => '生徒ID',
            'phone_number'  => '電話番号',
        );

        //Message validation
        $message = [
            'student_id.integer'         => ':attribute'.config('validation.integer'),
            'phone_number.numeric'       => ':attribute'.config('validation.numeric'),
            'phone_number.digits_between'=> ':attribute'.config('validation.digits_between'),
            'to_date.after_or_equal'     => config('validation.after_or_equal'),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

    /**
     * Display user datatable.
     * @return JsonResponse
     * @throws \Exception
     */
    public function studentDataTable()
    {
        $students = $this->studentRepository->studentDataTable();

        return Datatables::of($students)
            ->addColumn('checkbox', function ($student) {
                return '<input type="checkbox" class="chk_item" value="' .$student->id. '" name="user_id" />';
            })
            ->addColumn('td_hiden2', function ($student) {
                return '';
            })
            ->addColumn('created_at', function ($student) {
                return Helper::formatDate(Timezone::convertToLocal(Carbon::parse($student->created_at), 'Y-m-d H:i:s'));
//                return Helper::formatDate($student->created_at);
            })
            ->addColumn('action', function ($student) {
                return '<a href="'. route('admin.student.detail', $student->id) .'" class="btn btn-primary btn-sm btn-flat">詳細</a>';
            })
            ->addColumn('membership_status', function ($student) {
                if (empty($student->membership_status)) {
                    return config('constants.no_data');
                } else {
                    if ($student->membership_status == config('constants.membership.id.free')) {
                        return config('constants.membership.name.free');
                    } elseif ($student->membership_status == config('constants.membership.id.premium_trial')) {
                        return config('constants.membership.name.premium_trial');
                    } elseif ($student->membership_status == config('constants.membership.id.premium')) {
                        return config('constants.membership.name.premium');
                    } elseif ($student->membership_status == config('constants.membership.id.Special')) {
                        return config('constants.membership.name.Special');
                    } elseif ($student->membership_status == config('constants.membership.id.other_company')) {
                        return config('constants.membership.name.other_company');
                    } else {
                        return config('constants.no_data');
                    }
                }
            })
            ->rawColumns(['action', 'checkbox', 'created_at', 'td_hiden2'])
            ->make(true);
    }

    /**
     * Create student.
     * @return View
     */
    public function create()
    {
        $company = DB::table('company')->get();
        return view('admin.managers.students.create', ['company' => $company, 'nationalities' => config('nation'), 'phoneNumber' => config('phone_number')]);
    }

    /**
     * Create student form.
     *
     * @param TeacherRequest $request
     * @return RedirectResponse
     */
    public function addStudent(StudentRequest $request)
    {
        $input = $request->all();
        $check = $this->studentRepository->createStudent($input);
        if ($check['status']) {
            return redirect(route('admin.student.index'))->with('success', $check['message']);
        } else {
            return redirect(route('admin.student.create'))->withInput()->with('error_isset_email', $check['message']);
        }
    }

    /**
     * DeleteAll
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request)
    {
        $input = $request->all();
        $check = $this->studentRepository->deleteAll($input);
        if ($check) {
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }

    /**
     * Detail student
     * @return View
     */
    public function detail($user_id)
    {
        $nationality = config('nation');
        $phoneNumber = config('phone_number');
        $company = DB::table('company')->get();
        $studentInformation = $this->studentRepository->getStudentInformationById($user_id);
        return view('admin.managers.students.detail',compact('nationality','phoneNumber', 'studentInformation', 'company'));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function resetPassword($id) {
        $is_success = $this->studentRepository->resetPasswordForStudentById($id);
        if($is_success) {
            return $this->responseSuccess(null, __('validation_custom.M064'));
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

        $attributes = [
            'nickname' => 'ニックネーム',
            'year' => '年',
            'month' => '月',
            'day' => '日',
            'sex' => '性別',
            'phone_number' => '電話番号',
            'nationality' => '国籍',
            'birthday' => '生年月日'
        ];


        $message = [
            'nickname.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'nickname.max'                      => __('validation_custom.M003',['attribute'=>':attribute','min'=> '1', 'max' => '50']),
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
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {

            //update profile
            $is_success = $this->studentRepository->updateProfileForStudentById($input, $id);
            if($is_success) {
                return $this->responseSuccess($is_success,__('validation_custom.M027'));
            }
            else {
                return $this->responseSuccess(null,__('validation_custom.M028'));
            }
        }
    }

    public function refundCoin(Request $request, $id) {
        $input = $request->only('theNumberOfCoin');
        if(is_numeric($input['theNumberOfCoin'])) {
            $input['theNumberOfCoin'] = (int) $input['theNumberOfCoin'];
        }
        $rules['theNumberOfCoin'] = 'bail|numeric|min:0|not_in:0|digits_between:1,11';
        $attributes = [
            'theNumberOfCoin' => '返却のコイン数',
        ];

        $message = [
            'theNumberOfCoin.numeric'              => __('validation_custom.M006',['attribute'=>':attribute']),
            'theNumberOfCoin.min'                  => __('validation_custom.M056'),
            'theNumberOfCoin.not_in'               => __('validation_custom.M056'),
            'theNumberOfCoin.digits_between'       => __('validation_custom.M018',['attribute'=>':attribute','max' => '9']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {

            //update profile
            $student_coin = $this->studentRepository->refundCoinForStudentById($input, $id);
            if(!is_null($student_coin)) {
                return $this->responseSuccess($student_coin,__('validation_custom.M060'));
            }
            else {
                return $this->responseSuccess(null,__('validation_custom.M028'));
            }
        }
    }
}

