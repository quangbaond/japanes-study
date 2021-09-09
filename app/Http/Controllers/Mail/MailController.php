<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Create mail
     *
     * @return Renderable
     */
    public function createMail()
    {
        return view('admin.mail.create');
    }

    public function sendMail(Request $request)
    {
        $users = [
            [
                'email' => 'vinhppvk@mcrew-tech.com'
            ]
        ];
        $message = [
            'type' => 'Create task',
            'task' => 'Task',
            'content' => 'has been created!',
        ];
        SendEmail::dispatch($message, $users)->delay(now()->addMinute(1));
        dd('Send mail success');
    }

}
