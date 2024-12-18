<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param string $token
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recuperar senha')
                    ->view('mail.custom_reset_password')
                    ->with([
                        'empresa' => $this->data['empresa'],
                        'url' => env('APP_URL').'password/reset/'. $this->data['token'].'?email='.$this->data['email'],
                    ]);
    }
}
