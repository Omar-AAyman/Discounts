<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $get_user_email;
    public $get_user_first_name;
    public $get_user_last_name;

    public $validToken;

    /**
     * Create a new message instance.
     */
    public function __construct($get_user_email,$validToken,$get_user_first_name,$get_user_last_name)
    {
        $this->get_user_email = $get_user_email;
        $this->validToken = $validToken;
        $this->get_user_first_name = $get_user_first_name;
        $this->get_user_last_name = $get_user_last_name;


    }

    public function build()
    {
        return $this->view('emails.verifyToken')->with([
            'user_email' => $this->get_user_email,
            'validToken' => $this->validToken,
            'user_first_name' => $this->get_user_first_name,
            'user_last_name' => $this->get_user_last_name,

        ]);
    }

}