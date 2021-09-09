<?php
namespace App\Http\Controllers\Admin\Managers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Repositories\Admin\Managers\AdminRepository;
use App\Rules\CheckDateRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Timezone;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller {

    private $adminRepository;
    public function __construct( AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index() {

        $phoneNumber = config('phone_number');
        return view('admin.managers.Admin.index', compact('phoneNumber'));
    }

    public function getListAdmins(Request $request) {
        $input = $request->only('admin_id', 'email', 'phone_number', 'role', 'area_code', 'from_date', 'to_date');

        $listAdmins = $this->adminRepository->getListAdmins($input);

        return DataTables::of($listAdmins)
            ->addColumn('action', function ($admin) {
                return '<a href="'. route('admin.admin-list.detail', $admin->id) .'" class="btn btn-sm btn-primary btn-flat">詳細</a>';
            })
            ->addColumn('created_at', function ($admin) {
                return Helper::formatDate(Timezone::convertToLocal(Carbon::parse($admin->created_at), 'Y-m-d H:i:s'));
            })
            ->addColumn('checkbox', function ($admin) {
                if($admin->id == Auth::id()) {
                    return '<input type="checkbox" class="" value="' .$admin->id. '" name="teacher_id" disabled="disabled" />';
                }
                elseif(Auth::user()->role == config('constants.role.child-admin'))  {
                    return '<input type="checkbox" class="" value="' .$admin->id. '" name="teacher_id" disabled="disabled" />';
                }
                return '<input type="checkbox" class="chk_item" value="' .$admin->id. '" name="teacher_id" />';
            })
            ->addColumn('role', function ($admin) {
                if ($admin->role == config('constants.role.admin')) {
                    return "<span>親</span>";
                }
                if ($admin->role == config('constants.role.child-admin')) {
                    return "<span>子</span>";
                }
            })
            ->addColumn('originalSearch', function ($admin) {
                return $admin->created_at;
            })
            ->rawColumns(['action','created_at','checkbox', 'role', 'originalSearch'])
            ->make(true);
    }

    public function validateSearchForm(Request $request) {
        $input = $request->only('admin_id', 'email', 'phone_number', 'role', 'area_code', 'from_date', 'to_date');
        //Rule validation
        $rules = [];
        $input['admin_id'] != '' ? $rules['admin_id'] = 'integer' : $rules['admin_id'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'numeric|digits_between:1,11' : $rules['phone_number'] = '';
        ($input['from_date'] != '' && $input['to_date'] != '') ? $rules['to_date'] = 'after_or_equal:from_date' : $rules['to_date'] = '';

        // Set name for field
        $attributes = array(
            'admin_id'    => 'アドミンID',
            'phone_number'  => '電話番号',
        );

        //Message validation
        $message = [
            'admin_id.integer'           => ':attribute'.config('validation.integer'),
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

    public function deleteAdmins(Request $request) {
        $input = $request->only('user_id');
        $isSuccess = $this->adminRepository->deleteAdmins($input);
        if($isSuccess) {
            return $this->responseSuccess();
        }
        return $this->responseError();
    }

    public function create()
    {
        return view('admin.managers.Admin.create', [
            'nationalities' => config('nation'),
            'phoneNumber' => config('phone_number')
        ]);
    }

    public function store(AdminRequest $request) {
        $input = $request->all();
        $check = $this->adminRepository->storeAdmin($input);
        if ($check['status']) {
            return redirect(route('admin.admin-list'))->with('success', $check['message']);
        } else {
            return redirect(route('admin.admin-list.create'))->withInput()->with('error_isset_email', $check['message']);
        }
    }

    /**
     * admin detail
     * @return View
     */
    public function detail($user_id)
    {
        $nationalities = config('nation');
        $phoneNumber = config('phone_number');
        $adminInformation = $this->adminRepository->getStudentInformationById($user_id);
        return view('admin.managers.Admin.detail',compact('nationalities','phoneNumber', 'adminInformation'));
    }

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
        $rules['nickname'] = 'required|max:50' .
            '';
        isset($input['sex'])  ? $rules['sex'] = 'numeric|in:'.$sex : $rules['sex'] = '';
        $input['phone_number'] != '' ? $rules['phone_number'] = 'bail|numeric|digits_between:1,11' : $rules['phone_number'] = '';
        $input['area_code'] != '' ? $rules['area_code'] = 'in:'. $area_code : $rules['area_code'] = '';
        $input['nationality'] != '' ? $rules['nationality'] = 'in:'.$nation_code : $rules['nationality'] = '';
        isset($input['image_photo']) ? $rules['image_photo'] = 'mimes:jpeg,jpg,png,gif|max:5120' : $rules['image_photo'] = '';


        $attributes = [
            'year' => __('student.birthday'),
            'month' => __('student.birthday'),
            'day' => __('student.birthday'),
            'sex' => __('student.gender'),
            'phone_number' => __('student.phone_number'),
            'nationality' => __('student.nationality'),
            'image_photo' => __('student.photo'),
            'birthday' => __('student.birthday'),
            'nickname' => 'ニックネーム'
        ];


        $message = [
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
            'nickname.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'nickname.max'                      => __('validation_custom.M003',['attribute'=>':attribute','min' => '1','max' => '50']),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            $status = $this->adminRepository->editAdmin($input);
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
    public function changePassword(Request $request) {
        $data = $request->all();

        $attributes = [
            'old_password' => '現在のパスワード',
            'new_password' => '新しいパスワード',
            'new_password_confirmation' =>  '新しいパスワード（確認）'
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

        if ($this->adminRepository->changePassword($data)) {
            return $this->responseSuccess(null, __('validation_custom.M059'));
        } else {
            return $this->responseError(null, __('validation_custom.M028'));
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function resetPassword($id) {
        $is_success = $this->adminRepository->resetPasswordForAdminById($id);
        if($is_success) {
            return $this->responseSuccess(null, __('validation_custom.M068'));
        }
        return $this->responseError();
    }
}
