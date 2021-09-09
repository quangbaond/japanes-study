<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\students\ProfileRepository;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use Timezone;
use Carbon\Carbon;
use App\Models\Receiver;
use App\Models\Notifications;
class NotificationController extends Controller
{

    //
     /**
     * Display a listing of the resource.
     *
     */
    protected $profileRepository;

    function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    /**
     * Display notification.
     * @author quangbaorp
     *
     * @return View
     */
    public function index()
    {
        // Check cancelling premium for user
        $this->cancellingPremium();

        return view('admin.students.notification.index');
    }
     /**
     * Detail notification.
     * @author quangbaorp
     *
     * @return View
     */
    public function detail($id)
    {
        $data = [];
        $notificationUser =  Notifications::where('notifications.id' , $id)
            ->select('notifications.*', 'receiver.read_at', 'receiver.user_id')
            ->leftJoin('receiver' , 'notifications.id' , '=' , 'receiver.notification_id')
            ->where('notifications.receiver_class' , 4)
            ->where('receiver.user_id' , Auth::id())
            ->first();
        $notificationAllUser = Notifications::where('notifications.id' , $id)
            ->where (function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('receiver_class' , 3);
                });
                $query->orWhere('receiver_class' , 1);
            })
            ->first();
        if (empty($notificationUser)) {// Case: not isset data
            if (empty($notificationAllUser)){
                abort(404);
            }
            else {
                $data = $notificationAllUser;
            }
        } else { // Case: isset data
            $data = $notificationUser;
            if($data->read_at == null && $data->user_id == Auth::user()->id){ // Case: notification not read
                Receiver::where('notification_id' , $id)
                    ->where('user_id' , Auth::user()->id)
                    ->update([
                        'read_at' => Carbon::now()
                    ]);
            }
        }
        return view('admin.students.notification.detail' , compact('data'));

    }
    /**
     * @return mixed
     * @throws \Exception
     */
    public function notificationsDatatable()
    {
        $notifications = $this->profileRepository->notificationsDataTable();
        return Datatables::of($notifications)
            ->addColumn('title', function ($notifcation) {
                if ($notifcation->read_at == null) {
                    return '<strong>'.$notifcation->title.'</strong>';
                } else {
                    return $notifcation->title;
                }

            })
            ->addColumn('created_by', function ($notifcation) {
                if ($notifcation->read_at == null) {
                    return '<strong>'.$notifcation->email.'</strong>';
                } else {
                    return $notifcation->email;
                }

            })
            ->addColumn('user_created_at', function ($notifcation) {
                if ($notifcation->read_at == null) {
                    return '<strong>'.Helper::formatDate($notifcation->created_at).'</strong>';
                } else {
                    return Helper::formatDate($notifcation->created_at);
                }

            })
            ->addColumn('btn_notification_detail', function ($notifcation) {
                return '<a class="btn btn-flat btn-primary btn-sm text-center" href="'.route('student-notification-detail',$notifcation->id).'">'.__('student.detail').'</a>';
            })
            ->rawColumns(['action','user_created_by','checkbox','btn_notification_detail', 'created_by', 'user_created_at', 'title'])
            ->make(true);

    }
    public function notificationListValidation(Request $request)
    {
        // Data request
        $input = $request->all();
        // Rule validation
        $rules = [];
        ($input['created_at_from'] != '' && $input['created_at_to'] != '') ? $rules['created_at_to'] = 'after_or_equal:created_at_from' : $rules['created_at_to'] = '';
        // Message validation
        $message = [
            'created_at_to.after_or_equal'  => __('validation_custom.M010'),
        ];

        $validator = Validator::make($input, $rules, $message);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }

}
