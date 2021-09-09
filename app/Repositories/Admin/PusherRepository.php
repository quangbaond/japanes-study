<?php
namespace App\Repositories\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\MailService;
use MacsiDigital\Zoom\Facades\Zoom;
use Pusher\Pusher;

class PusherRepository
{
    private $options = array(
        'cluster' => 'ap1',
        'encrypted' => true
    );
    private  $pusher;

    /**
     * PusherRepository constructor.
     * @throws \Pusher\PusherException
     */
    public function __construct()
    {
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $this->options
        );
    }

    /**
     * @param $channel
     * @param $send_to
     * @param $data
     * @return bool
     * @throws \Pusher\PusherException
     */
    public function sendMessage($channel, $send_to, $data) {
        if (!is_null(Cache::get('user-is-online-' . $send_to))) {
            $this->pusher->trigger( $channel . '-' . $send_to, 'my-event', $data);
            return true;
        } else {
//            dd(123);
            return true; // push notification with other methods
        }
    }

    public function sendMessageWhenTeacherCancel($channel, $send_to, $data) {
        $this->pusher->trigger( $channel . '-' . $send_to, 'my-event', $data);
        return true;
    }
    public function sendNotify($channel, $send_to, $data) {
        $this->pusher->trigger( $channel . '-' . $send_to, 'my-event', $data);
        return true;
    }
}
