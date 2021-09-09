<?php

namespace App\Http\Controllers\Admin\Managers;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Managers\ProfileRepository;
use App\Rules\CheckDateRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Receiver;
use App\Models\User;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use Yajra\DataTables\DataTables;
use Pusher\Pusher;
use Carbon\Carbon;
use Timezone;

use Illuminate\Support\Str;

class NotificationController extends Controller
{
    protected $profileRepository;

    function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Display notification.
     * @author vinhppvk
     *
     * @return View
     */
    public function index()
    {
        return view('admin.managers.notification.index');
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

    public  function deleteNotification(Request $request) {
        $input = $request->all();
        $check = $this->profileRepository->deleteNotification($input);
        if ($check) {
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }
    /**
     * Validation search list notifications.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function notificationListValidation(Request $request)
    {
        // Data request
        $input = $request->all();
        // Rule validation
        $rules = [];
        ($input['created_at_from'] != '' && $input['created_at_to'] != '') ? $rules['created_at_to'] = 'after_or_equal:created_at_from' : $rules['created_at_to'] = '';

        $date_from = explode('/',$input['created_at_from']);
        $date_to = explode('/', $input['created_at_to']);

        $input['format_created_at_from'] = implode('-', $date_from);
        $input['format_created_at_to'] = implode('-', $date_to);
        $rules['format_created_at_from'] = [new CheckDateRule()];
        $rules['format_created_at_to'] = [new CheckDateRule()];

        // Message validation
        $message = [
            'created_at_to.after_or_equal'  => config('validation.after_or_equal'),
            'format_created_at_from.date_format'        => __('validation_custom.M020'),
            'format_created_at_to.date_format'          => __('validation_custom.M020'),
        ];


        $validator = Validator::make($input, $rules, $message);

        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }


    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNotification($id, Request $request) {
        $input = $request->all();
        $check = $this->profileRepository->updateNotification($id, $input);
        if($check) {
            return $this->responseSuccess();
        }
        else {
            return $this->responseError('error');
        }
    }

    /**
     * @return RedirectResponse
     */
    public function toListNotification() {
        try {
            $success = __('validation_custom.M027');
            return \redirect()->route('admin-notification')->with('success', $success);
        } catch (\Exception $e) {
            dd(123);
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function notificationsDatatable() {
        $notifications = $this->profileRepository->notificationsDataTable();
        return Datatables::of($notifications)
            ->addColumn('user_created_at', function ($notification) {
                if ($notification->read_at == NULL && $notification->user_id == Auth::id()) {
                    return '<strong>'.Helper::formatDate($notification->created_at).'</strong>';
                } else {
                    return Helper::formatDate($notification->created_at);
                }
            })
            ->addColumn('title', function ($notification) {
                if ($notification->read_at == NULL && $notification->user_id == Auth::id()) {
                    return '<strong>'.$notification->title.'</strong>';
                } else {
                    return $notification->title;
                }
            })
            ->addColumn('email', function ($notification) {
                if ($notification->read_at == NULL && $notification->user_id == Auth::id()) {
                    return '<strong>'.$notification->email.'</strong>';
                } else {
                    return $notification->email;
                }
            })
            ->addColumn('checkbox', function ($notification) {
                return '<input type="checkbox" class="chk_item" value="'.$notification->id.'" name="notification_id" />';
            })
            ->addColumn('btn_notification_detail', function ($notification) {
                return '<a class="btn btn-flat btn-primary btn-sm" href="'.route('admin.notification.detail',$notification->id).'">詳細</a>';
            })
            ->rawColumns(['action', 'title' , 'user_created_at', 'email','checkbox','btn_notification_detail'])
            ->make(true);
    }
    /**
     * Create notification.
     * @author vinhppvk
     *
     * @return View
     */
    public function create()
    {
        return view('admin.managers.notification.create');
    }

    /**
     * Detail notification.
     * @author vinhppvk
     *
     * @return View
     */
    public function detail($id)
    {
        $data = $this->profileRepository->getNotificationById($id);

        $notification = $data['notification'];
        if($notification == NULL){
            abort(404);
        }
        $users = $data['users'];

        $checkReadOwner = DB::table('notifications')
            ->select('receiver.*')
            ->leftJoin('receiver','notifications.id','=','receiver.notification_id')
            ->where('receiver.notification_id','=', $id)
            ->where('receiver.user_id','=', Auth::id())
            ->first();

        if (!empty($checkReadOwner) && $checkReadOwner->read_at == null) {
            Receiver::where('notification_id', $id)
                ->where('user_id', Auth::id())
                ->update([
                    'read_at' => Carbon::now()
                ]);
        }
        return view('admin.managers.notification.detail',compact('notification','users'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function validateNotification(Request $request){
        //Data request
        $input = $request->all();

        //rule validation
        $rules = [];

        $rules['title'] = 'required|max:200';
        $rules['content'] = 'required|max:4294967295';
        $rules['from_date'] = 'required';
        $rules['to_date'] = 'required|after_or_equal:from_date';

        $attributes = [
            'title' => 'タイトル',
            'content' => '内容',
            'from_date' => '表示時間(From)',
            'to_date' => '表示時間(To)'
        ];

        $date_from = explode('/',$input['from_date']);
        $date_to = explode('/', $input['to_date']);

        $input['format_date_from'] = implode('-',$date_from);
        $input['format_date_to'] = implode('-',$date_to);

        $rules['format_date_from'] = [new CheckDateRule()];
        $rules['format_date_to'] = [new CheckDateRule()];

        $message = [
            'title.required'                            => __('validation_custom.M001',['attribute'=>':attribute']),
            'title.max'                                 => __('validation_custom.M003',['attribute'=>':attribute','min' => '1','max' => '200']),
            'content.required'                          => __('validation_custom.M001',['attribute'=>':attribute']),
            'content.max'                               => __('validation_custom.M003',['attribute'=>':attribute','min' => '1','max' => '4294967295']),
            'from_date.required'                        => __('validation_custom.M001',['attribute'=>':attribute']),
            'to_date.required'                          => __('validation_custom.M001',['attribute'=>':attribute']),
            'to_date.after_or_equal'                    => config('validation.after_or_equal'),
            'format_date_from.date_format'              => __('validation_custom.M020'),
            'format_date_to.date_format'                => __('validation_custom.M020'),
        ];

        $validator = Validator::make($input, $rules, $message)->setAttributeNames($attributes);
        if ($validator->fails()) {
            return $this->responseError(null, $validator->errors());
        } else {
            return $this->responseSuccess();
        }
    }
    /**
     * insert notification.
     * @author quangbaorp
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function insertNotification(Request $request)
    {
        $input = $request->all();
        $users = [];
        $message = [
            'title.required'         => 'タイトル'.config('validation.required'),
            'title.max'         => __('validation_custom.M003'),
            'description.required'       => '内容'.config('validation.required'),
            'description.max' => __('validation_custom.M003'),
            'end_date.after_or_equal'  => __('validation_custom.M010'),
            'start_date.required'         => '表示時間(From) '.config('validation.required'),
            'end_date.required'         => '表示時間(To) '.config('validation.required'),
            'address.required' => '宛名'.config('validation.required'),
        ];

        if($request->receiverClass != '4'){
            $validator = Validator::make($input, [
                'end_date' => 'required|after_or_equal:start_date',
                'start_date' => 'required',
                'title' => 'required|max:200',
                'description' => 'required|max:4294967295',
            ], $message);
        }
        else {
            $validator = Validator::make($input, [
                'title' => 'required|max:200',
                'description' => 'required|max:4294967295',
                'address' => 'required',
            ], $message);
        }
        if ($validator->fails()) {
            return redirect(route('admin-notification.create'))->with('error' , __('validation_custom.CM001'))->withErrors($validator)->withInput();
        } else {

            try {
                DB::beginTransaction();
                $notification_id =  Notifications::insertGetId([
                    'title' => $request->title,
                    'content' => $request->description,
                    'receiver_class' => $request->receiverClass,
                    'start_date' => CarBon::parse($request->start_date),
                    'end_date' => CarBon::parse($request->end_date),
                    'created_by' => Auth::user()->id,
                    'created_at' => CarBon::now(),
                    'updated_at' => CarBon::now()
                ]);
                // Pusher realtime
                $options = array(
                    'cluster' => 'ap1',
                    'encrypted' => true
                );
                $pusher = new Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );
                $pusherBody['id'] = $notification_id;
                $pusherBody['title'] = Str::limit($request->title , 20);
                $pusherBody['content'] = $request->description;
                $pusherBody['created_at'] =  CarBon::now()->format('Y-m-d H:i:s');
                if($request->receiverClass == '4'){
                    $users = User::whereIn('id' , $request->address)->get();
                }
                else if($request->receiverClass == '1'){
                    $pusher->trigger('notification-all-user', 'my-event',$pusherBody);
                }
                if(count($users) > 0 ){
                    for($i = 0; $i < count($users); $i++){
                        $data[] = [
                            'notification_id' => $notification_id ,
                            'user_id' => $users[$i]->id,
                            'read_at' => NULL
                        ];
                        $pusherBody['user_to'] = $users[$i]->id;
                        $pusher->trigger('notification-user-'.$pusherBody['user_to'], 'my-event',$pusherBody);
                    }
                    $chunks = collect($data)->chunk(3);
                    foreach($chunks as $chunk){
                        Receiver::insert($chunk->toArray());
                    }
                }
                DB::commit();
                return redirect(route('admin-notification'))->with('success' , __('validation_custom.M008'));
            } catch (Throwable $e) {
                DB::rollback();
            }
        }
    }

}

