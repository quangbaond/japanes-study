<?php

namespace App\Repositories\Admin\Managers;

use App\Http\Controllers\TimezoneController;
use Carbon\Carbon;
use Timezone;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
    protected $timezone;

    public function __construct(TimezoneController $timezone)
    {
        $this->timezone = $timezone;
    }
    /**
     * @return mixed
     */
    public function index()
    {
        return view('admin.managers.users.index');
    }

    /**
     * @return Collection
     */
    public function teacherDataTable()
    {
        $query = DB::table('users')
            ->select(
                'users.id',
                'users.nickname',
                'users.email',
                'users.zalo_id',
                'users.status',
                'users.last_seen',
                'users.last_login_at',
                'users.created_by',
                'users.updated_by',
                'users.deleted_by',
                'users.created_at as created_at_user',
                'user_information.image_photo',
                'user_information.birthday',
                'user_information.age',
                'user_information.sex',
                'user_information.nationality',
                'user_information.company_id',
                'user_information.phone_number',
                'user_information.created_at'
            )
            ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
            ->where('users.deleted_at', null)
            ->where('users.role', config('constants.role.teacher'));

        // Search teacher id
        if (!empty($_GET["teacher_id"])) {
            $query->where('users.id','LIKE',$_GET["teacher_id"])->get();
        }
        // Search phone_number
        if (!empty($_GET["phone_number"])) {
            $query->where('user_information.phone_number','LIKE','%'.$_GET["phone_number"].'%')->get();
        }
        // Search nationality
        if (!empty($_GET["nationality"])) {
            $query->where('user_information.nationality','LIKE','%'.$_GET["nationality"].'%')->get();
        }
        // Search mail
        if (!empty($_GET["email"])) {
            $query->where('users.email','LIKE','%'.$_GET["email"].'%')->get();
        }
        // Search sex
        if (!empty($_GET["sex"])) {
            $query->where('user_information.sex','=', $_GET["sex"])->get();
        }
        //Search age
        if (!empty($_GET["age_from"]) && empty($_GET["age_to"])) {
            $query->where('user_information.age','>=', $_GET["age_from"]);
        }
        if (empty($_GET["age_from"]) && !empty($_GET["age_to"])) {
            $query->where('user_information.age','<=', $_GET["age_to"]);
        }
        if (!empty($_GET["age_from"]) && !empty($_GET["age_to"])) {
            $query->where('user_information.age','>=', $_GET["age_from"]);
            $query->where('user_information.age','<=', $_GET["age_to"]);
        }
        // Search created_at
        if (!empty($_GET["created_at_from"]) && empty($_GET["created_at_to"])) {
            $query->whereDate('users.created_at','>=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["created_at_from"])->format('Y-m-d H:i:s')))));
        }
        if (empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
            $query->whereDate('users.created_at','<=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["created_at_to"])->format('Y-m-d H:i:s')))));
        }
        if (!empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
            $query->whereDate('users.created_at','>=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["created_at_from"])->format('Y-m-d H:i:s')))));
            $query->whereDate('users.created_at','<=',  date('Y-m-d', strtotime($this->timezone->convertFromLocal(Carbon::parse($_GET["created_at_to"])->format('Y-m-d H:i:s')))));
        }
//
//        $data = $query->get();
//
//        $teachers = $data->filter(function($value , $key) {
//            // Search created_at
//            if (!empty($_GET["created_at_from"]) && empty($_GET["created_at_to"])) {
//                return Timezone::convertToLocal(Carbon::parse($value->created_at_user) , "Y-m-d")  >= date('Y-m-d', strtotime($_GET["created_at_from"]));
//            }
//            if (empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
//                return Timezone::convertToLocal(Carbon::parse($value->created_at_user) , "Y-m-d")  <= date('Y-m-d', strtotime($_GET["created_at_to"]));
//            }
//            if (!empty($_GET["created_at_from"]) && !empty($_GET["created_at_to"])) {
//                return
//                    Timezone::convertToLocal(Carbon::parse($value->created_at_user) , "Y-m-d")  >= date('Y-m-d', strtotime($_GET["created_at_from"])) and
//                    Timezone::convertToLocal(Carbon::parse($value->created_at_user) , "Y-m-d")  <= date('Y-m-d', strtotime($_GET["created_at_to"]));
//            }
//            return $value;
//        });
        $teachers = $query->get();
        $nationArray = config('nation');
        foreach ($teachers as $teacher) {
            $key= $teacher->nationality;
            if(isset($key)){
                $teacher->nationality = $nationArray[$key];
            }
        }
        return $teachers;
    }

    /**
     * @return Builder
     */
    public function userDataTable()
    {
        return DB::table('users')
            ->select('*')
            ->where('deleted_at', null);
    }

    /**
     * @param $fieldTable
     * @param $valueSearch
     * @return Collection
     */
    public function userDataTableSearch($fieldTable, $valueSearch)
    {
        return DB::table('users')
            ->select('*')
            ->where($fieldTable,'LIKE','%'.ltrim($valueSearch, 0).'%')->get();
    }

    /**
     * @param $id
     * @return object
     */
    public function detail($id)
    {
        try {
            $user = DB::table('users')
                ->select('*')
                ->where('id', $id)
                ->first();
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * @param $email
     * @return object
     */
    public function detailByEmail($email)
    {
        try {
            $user = DB::table('users')
                ->select('*')
                ->where('email', $email)
                ->first();
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * @param $id
     * @return object
     */
    public function edit($id)
    {
        try {
            $user = DB::table('users')
                ->select('*')
                ->where('id', $id)
                ->first();
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update user
     * @param $input
     * @return bool
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            // Update data user
             DB::table('users')
                ->where('id', $input['user_id'])
                ->update([
                    'name'        => $input['name'],
                    'email'       => $input['email'],
                    'role'        => (int)$input['role'],
                    'auth'        => (int)$input['auth'],
                    'status'      => (int)$input['status'],
                    'user_create' => (int)$input['user_create'],
                ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
    /**
     * @param $email
     * @return object
     */
    public function create($input)
    {
        DB::beginTransaction();
        try {
            // Update data user
            $user = DB::table('users')
                ->select('*')
                ->where('email', $input['email'])
                ->first();
            if($user){
                DB::table('users')->where('email',$input['email'])->update(['deleted_at' => null]);
            } else {
                DB::table('users')->insert(['email' => $input['email'],'nickname' => $input['nickname'],'deleted_at' => null,'role' => $input['role'],'auth' => 1,'password' => bcrypt($input['pass']),'created_at' => date('Y-m-d H:i:s',time())]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            print_r($e->getTraceAsString());
            DB::rollback();
            return false;
        }
    }
}
