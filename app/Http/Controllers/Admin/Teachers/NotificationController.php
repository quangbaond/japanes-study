<?php

namespace App\Http\Controllers\Admin\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Timezone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Helpers\Helper;
use App\Models\Notifications;
use App\Models\Receiver;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\teachers\TeacherRepository;
class NotificationController extends Controller
{
    //
     /**
     * Display a listing of the resource.
     *
     */
    protected $teacherRepository;
    function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Display notification.
     * @author quangbaorp
     *
     * @return View
     */
    public function index()
    {
        return view('admin.teachers.notification.index');
    }

    /**
     * Detail notification.
     * @author quangbaorp
     *
     * @param $id
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
                    $subQuery->where('receiver_class' , 2);
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
        return view('admin.teachers.notification.detail' , compact('data'));

    }

    public function notificationsDatatable() {
        $notifications = $this->teacherRepository->notificationsDataTable();
        return Datatables::of($notifications)
            ->addColumn('title', function ($notification) {
                if ($notification->read_at == null) {
                    return '<strong>'.$notification->title.'</strong>';
                } else {
                    return $notification->title;
                }

            })
            ->addColumn('user_created_at', function ($notification) {
                if ($notification->read_at == null) {
                    return '<strong>'.Helper::formatDate($notification->created_at).'</strong>';
                } else {
                    return Helper::formatDate($notification->created_at);
                }
            })
            ->addColumn('email', function ($notification) {
                if ($notification->read_at == null) {
                    return '<strong>'.$notification->email.'</strong>';
                } else {
                    return $notification->email;
                }

            })
            ->addColumn('btn_notification_detail', function ($notifcation) {
                return '<a class="btn btn-flat btn-primary btn-sm text-center" href="'.route('teacher-notification-detail',$notifcation->id).'">詳細</a>';
            })
            ->rawColumns(['action','user_created_at','checkbox','btn_notification_detail', 'title', 'email'])
            ->make(true);
    }
    /**
     * @return JsonResponse
     */
    public function getEmail(Request $request) {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('users')->select('id', 'email')
                ->where('email','LIKE',"%$search%")
                ->get();
        }
        return response()->json($data);
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
            'created_at_to.after_or_equal'  => config('validation.after_or_equal'),
        ];

        $validator = Validator::make($input, $rules, $message);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }
}
