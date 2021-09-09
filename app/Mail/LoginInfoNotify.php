<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginInfoNotify extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $view = 'mails.mail-login-teacher';
        if($this->data['role'] == config('constants.role.student')){
            $view = 'mails.mail-login-student';
        }
        return $this->from('khoivinhphan@gmail.com')
            ->view($view, ['data' => $this->data])
            ->subject($this->data['title']);
    }
}
