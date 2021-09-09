<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return [
            'notification-user-' . $this->data['user_to'],
            'notification-all-user',
//            'notification-open-lesson-teacher-' . $this->data,
//            'notification-open-lesson-student-' . $this->data,
            'notification-student-teacher-my-page-' . $this->data,
            'notification-student-notify-teacher-' . $this->data,
            'notification-admin-notify-' . $this->data,
        ];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }

}
