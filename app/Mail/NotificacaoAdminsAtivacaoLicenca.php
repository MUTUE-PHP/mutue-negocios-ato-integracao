<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacaoAdminsAtivacaoLicenca extends Mailable
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
        $this->to($this->data['emails']);
        $this->from(env('MAIL_FROM_ADDRESS'));
        $this->subject('Ativacao da licença');
        return $this->view("mail.NotificaAdminsAtivacaoLicenca", $this->data)
            ->attach($this->data['fatura'], [
                'as' => 'Fatura liquidada'
            ])->attach($this->data['reciboPagamento'], [
                'as' => 'Recibo de pagamento'
            ]);
    }
}
