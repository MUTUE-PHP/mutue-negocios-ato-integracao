<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailRecuperacaoSenhaMobile extends Mailable
{
   // use Queueable;
   use SerializesModels;


    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
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
        $this->to($this->data['email']);
        $this->subject('Recuperação de senha');
        return $this->view("mail.recuperacaoSenhaMobile", $this->data);
    }
}
