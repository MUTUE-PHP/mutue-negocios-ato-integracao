<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacaoReinvioPagamentoVendaOnline extends Mailable
{
    use Queueable, SerializesModels;

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
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['assunto'])
            ->from(env('MAIL_FROM_ADDRESS_ONLINE'), 'MUTUE-VENDAS-ONLINE')
            ->to($this->data['emails'])
            ->view('mail.pagamentoReinviadoVendaOnline', $this->data);

    }
}
