<?php

namespace App\Http\Controllers\Admin;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\PushNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Pusher\Pusher;

class SendNotificationController extends Controller
{
    public function create()
    {
        return view('admin.notification.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $user = User::find($input['user']); // user này sẽ nhận được thông báo
        $data = $request->only([
            'title',
            'content',
        ]);
        // Add params in data
        $data['user_from'] = Auth::user()->id;
        $data['user_to'] = (Int)$input['user'];

        $user->notify(new PushNotification($data));
        $data['id'] = $user->notifications->first()->id;
        $data['created_at'] = $user->notifications->first()->created_at->diffForHumans();

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

        //Push notification for one user
        $pusher->trigger('notification-user-'.$data['user_to'], 'my-event', $data);
        //Push notification for all user
        //$pusher->trigger('notification-all-user', 'my-event', $data);

        return redirect()->route('notification.create')->with('success', __('notification.update-success'));
    }

    public function detail(Request $request)
    {
        $input = $request->all();
        $notification = Auth::user()->notifications()->where('id', $input['id'])->first();
        if ($notification) {
            if ($notification->read_at == null) {
                $read_at = 0;
            } else {
                $read_at = 1;
            }

            // Is read
            $notification->markAsRead();
            $data = [
                'content' => View::make('admin.notification.append.content', compact('notification'))->render(),
                'read_at' => $read_at
            ];
            return $this->responseSuccess($data);
        }
        return $this->responseError();
    }
}
