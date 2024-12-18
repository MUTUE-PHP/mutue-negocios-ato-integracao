<?php

namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacaoClienteAtivacaoLicenca extends Mailable
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
        $this->from(env('MAIL_FROM_ADDRESS'));
        $this->subject('Ativação da licença');
        return $this->view("mail.NotificacaoClienteAtivacaoLicenca", $this->data);
    }
}
