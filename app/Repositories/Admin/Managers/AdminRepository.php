<?php
namespace App\Repositories\Admin\Managers;

use App\Http\Controllers\TimezoneController;
use App\Models\UserInformation;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminRepository {

    private $timezone;
    private $mailServices;

    public function __construct(TimezoneController $timezone, MailService $mailService)
    {
        $this->timezone = $timezone;
        $this->mailServices = $mailService;
    }

    public function getListAdmins($data) {
        $listAdmins = DB::table('users')
            ->select(
                'users.id',
                'users.nickname',
                'users.email',
                'users.role',
                'user_information.phone_number',
                'user_information.area_code',
                'users.created_at'
            )
            ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
            ->whereIn('role', [config('constants.role.admin'), config('constants.role.child-admin')])
            ->whereNull('users.deleted_at')
            ->where('users.auth', '1');
        if(!empty($data['admin_id'])) {
            $listAdmins->where('users.id', '=', $data['admin_id']);
        }
        if(!empty($data['email'])) {
            $listAdmins->where('users.email', 'like', '%' .$data['email'] . '%');
        }
        if(!empty($data['area_code'])) {
            $listAdmins->where('user_information.area_code', 'like',  '%' . $data['area_code'] . '%');
        }
        if(!empty($data['phone_number'])) {
            $listAdmins->where('user_information.phone_number', 'like',  '%' . $data['phone_number'] . '%');
        }
        if(!empty($data['role'])) {
            $listAdmins->where('users.role', '=',  $data['role']);
        }
        if(!empty($data['from_date'])) {
            $listAdmins->where('users.created_at', '>=', date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($data["from_date"] . " " . "00:00:00")->format('Y-m-d H:i:s')))));
        }
        if(!empty($data['to_date'])) {
            $listAdmins->where('users.created_at', '<=', date('Y-m-d H:i:s', strtotime($this->timezone->convertFromLocal(Carbon::parse($data["to_date"]. " " . "23:59:59")->format('Y-m-d H:i:s')))));
        }

        return $listAdmins->get();
    }

    public function deleteAdmins($data) {
        DB::beginTransaction();
        try{
            DB::table('users')->whereIn('users.id', $data['user_id'])
            ->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::user()->id
            ]);
            DB::commit();
            return true;
        }  catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    protected function generateRandomString($length = 9) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    public function storeAdmin($input) {
        $user = DB::table('users')->select('id', 'role', 'deleted_at')->where('email', $input['email'])->first();
        if ($user) {
            if (empty($user->deleted_at) || ( $user->role != config('constants.role.admin') && $user->role != config('constants.role.child-admin') ) ) {
                // Error
                return [
                    'status'    => false,
                    'message'   => config('constants.email_isset'),
                ];
            }
            // Update student
            return [
                'status'    => $this->updateAdmin($input, $user),
                'message'   => config('constants.register_success')
            ];
        } else {
            // Insert student
            return [
                'status'    => $this->insertAdmin($input),
                'message'   => config('constants.register_success')
            ];
        }
    }

    public function updateAdmin($input, $user)
    {
        DB::beginTransaction();
        try {
            // Update table users
            $password = $this->generateRandomString(9);
            DB::table('users')
                ->where('email',$input['email'])
                ->update([
                    'nickname'      => $input['nickname'],
                    'password'      => bcrypt($password),
                    'role'          => $input['role'],
                    'last_seen'     => null,
                    'last_login_at' => null,
                    'deleted_at'    => null,
                    'updated_at'    => now(),
                ]);

            // Update AMZ S3
            $image_photo_update = null;
            // Delete image old in AMZ S3
            $image_photo = DB::table('user_information')->select('image_photo')->where('user_id', $user->id)->first();
            if (!empty($image_photo)) {
                $array = explode("/", $image_photo->image_photo);
                $img = max(array_keys($array));
                $this->deleteImageS3($array[$img]);
            }
            if (!empty($input['image_photo'])) {
                // Insert image new in AMZ S3
                $file = $input["image_photo"];
                $name = time() . rand();
                $filePath = $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

                // Exits url image amazon s3
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_photo_update = Storage::disk('s3')->url($filePath);
                }
            }

            // Update table user_information
            $birthday = !empty($input['year']) ? date('Y-m-d', strtotime($input['year'] . '-' . $input['month'] . '-' . $input['day'])) : null;
            $age = !empty($input['year']) ? (int)date_diff(date_create($birthday), date_create('today'))->y : null;
            $data = [
                'sex'               => !empty($input['sex']) ? $input['sex'] : null,
                'nationality'       => !empty($input['nationality']) ? $input['nationality'] : null,
                'phone_number'      => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'         => !empty($input['area_code']) ? $input['area_code'] : null,
                'image_photo'       => $image_photo_update,
                'birthday'          => $birthday,
                'age'               => $age,
                'updated_at'        => now(),
            ];
            DB::table('user_information')->where('user_id', $user->id)->update($data);


            $data_mail  = array(
                'nickname' => $input['nickname'],
                'email' => $input['email'],
                'password' => $password,
                'url' => route('login')
            );

            $this->mailServices->sendMailRegisterToNewAdmin($data_mail);

            // DB commit
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function insertAdmin($input) {
        DB::beginTransaction();
        try {
            // Insert table users with role: student

            $password = $this->generateRandomString(9);
            $user_id = DB::table('users')
                ->insertGetId([
                    'email'         => $input['email'],
                    'nickname'      => $input['nickname'],
                    'role'          => $input['role'] ?? '4',
                    'auth'          => '1',
                    'password'      => bcrypt($password),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

            // Insert AMZ S3
            if (!empty($input['image_photo'])) {
                $file = $input["image_photo"];
                $name = time() . rand();
                $filePath = $name;
                //dd($filePath);
                Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

                // Get url images
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_photo_insert = Storage::disk('s3')->url($filePath);
                } else {
                    $image_photo_insert = null;
                }
            } else {
                $image_photo_insert = null;
            }

            // Insert table user_information
            $birthday = !empty($input['year']) ? date('Y-m-d', strtotime($input['year'] . '-' . $input['month'] . '-' . $input['day'])) : null;
            $age = !empty($input['year']) ? (int)date_diff(date_create($birthday), date_create('today'))->y : null;
            $data = [
                'user_id'           => $user_id,
                'sex'               => !empty($input['sex']) ? $input['sex'] : null,
                'nationality'       => !empty($input['nationality']) ? $input['nationality'] : null,
                'phone_number'      => !empty($input['phone_number']) ? $input['phone_number'] : null,
                'area_code'         => !empty($input['area_code']) ? $input['area_code'] : null,
                'image_photo'       => $image_photo_insert,
                'birthday'          => $birthday,
                'age'               => $age,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
            DB::table('user_information')->insert($data);

            $data_mail  = array(
                'nickname' => $input['nickname'],
                'email' => $input['email'],
                'password' => $password,
                'url' => route('login')
            );

            $this->mailServices->sendMailRegisterToNewAdmin($data_mail);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    protected function deleteImageS3($name)
    {
        if (Storage::disk('s3')->delete( $name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getStudentInformationById($id) {
        try {
            return DB::table('users')
                ->select(
                    'users.id',
                    'users.email',
                    'users.nickname',
                    'users.role',
                    'user_information.birthday',
                    'user_information.user_id',
                    'user_information.age',
                    'user_information.sex',
                    'user_information.area_code',
                    'user_information.nationality',
                    'user_information.phone_number',
                    'user_information.image_photo',
                )
                ->leftJoin('user_information', 'user_information.user_id', '=', 'users.id')
                ->where('users.id', '=', $id)
                ->whereNull('users.deleted_at')
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function editAdmin($data) {
        DB::beginTransaction();
        try{
            // Update AMZ S3
            $image_photo_update = null;
            // Delete image old in AMZ S3
            $image_photo = DB::table('user_information')->select('image_photo')->where('user_id', $data['user_id'])->first();
            $image_photo_update = $image_photo->image_photo ?? $image_photo_update;
            if(isset($data['image_photo']) && !empty($data['image_photo'])) {
                //db co thi xoa anh cu
                if (!empty($image_photo)) {
                    $array = explode("/", $image_photo->image_photo);
                    $img = max(array_keys($array));
                    $this->deleteImageS3($array[$img]);
                }
                // Insert new image in AMZ S3
                $file = $data["image_photo"];
                $name = time() . rand();
                $filePath = $name;
                Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

                // Exits url image amazon s3
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_photo_update = Storage::disk('s3')->url($filePath);
                }
            }


            if(isset($data['check_avatar_image']) && $data['check_avatar_image'] === '2') {
                if (!empty($image_photo)) {
                    $array = explode("/", $image_photo->image_photo);
                    $img = max(array_keys($array));
                    $this->deleteImageS3($array[$img]);
                }
                $image_photo_update = null;
            }

            $age = $data['birthday'] != null ? (int)date_diff(date_create($data['birthday']), date_create('today'))->y : null;
            $data_profile = array(
                'user_id'           => $data['user_id'],
                'sex'               => !empty($data['sex']) ? $data['sex'] : null,
                'nationality'       => !empty($data['nationality']) ? $data['nationality'] : null,
                'phone_number'      => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'area_code'         => !empty($data['area_code']) ? $data['area_code'] : null,
                'image_photo'       => $image_photo_update,
                'birthday'          => $data['birthday'],
                'age'               => $age,
                'created_at'        => now(),
                'updated_at'        => now(),
            );
            DB::table('users')->where('id', $data['user_id'])
                ->update(
                    [
                        'nickname' => $data['nickname'],
                        'updated_at' => now()
//                        'role' => $data['role']
                    ]
                );
            $check_profile = DB::table('user_information')->select('id')->where('user_id', $data['user_id'])->first();

            if ( !empty($check_profile) ) {
                //Update user profile
                UserInformation::find($check_profile->id)->update($data_profile);
            } else {
                //Create user profile
                UserInformation::create($data_profile);
            }

            DB::commit();
            $data = [
                'image_photo' => $image_photo_update
            ];
            return $data;
        } catch(\Exception $e) {
            dd($e);
            DB::rollback();
            return null;
        }
    }

    public function changePassword($data) {
        $user = DB::table('users')->select('password')->where('id', '=', Auth::user()->id)->first();
        if ( password_verify($data['old_password'], $user->password) ) {
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update([
                    'password' => bcrypt($data['new_password'])
                ]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function resetPasswordForAdminById($id) {
        $password = $this->generateRandomString(9);
        DB::beginTransaction();
        try {
            DB::table('users')
                ->where('users.id', $id)
                ->update([
                    'password' => bcrypt($password),
                    'updated_at' => now()
                ]);
            $student = DB::table('users')->select('users.nickname',
                'users.email',
                'users.password')
                ->where('users.id', $id)->first();
            $data_mail = array('nickname' => $student->nickname,
                'email' => $student->email,
                'password' => $password,
                'title' => '[Study Japanese] パスワードリセット成功のお知らせ',
                'url' => route('login'));
            $content_page = 'mails.mail-reset-password-admin';
            $this->mailServices->sendInfoResetPassword($data_mail, $content_page);
            DB::commit();
            return true;
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
