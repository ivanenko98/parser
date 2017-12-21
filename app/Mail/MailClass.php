<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailClass extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $email;
    protected $msg;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param $subject
     * @param $name
     * @param $email
     * @param $msg
     */
    public function __construct($subject, $name, $email, $msg)
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->email = $email;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail')
            ->with(
                [
                    'subject' => $this->subject,
                    'name' => $this->name,
                    'email' => $this->email,
                    'data' => $this->msg,
                ]
            )
            ->subject($this->subject);
    }
}
