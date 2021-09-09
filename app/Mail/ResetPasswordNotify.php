<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotify extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    protected $content_page;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $content_page)
    {
        $this->content_page = $content_page;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('khoivinhphan@gmail.com')
            ->view($this->content_page, ['data' => $this->data])
            ->subject($this->data['title']);
    }
}
