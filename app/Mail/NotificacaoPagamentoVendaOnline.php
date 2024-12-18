<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacaoPagamentoVendaOnline extends Mailable
{
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
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['assunto'])
            ->to($this->data['emails'])
            ->view("mail.{$this->data['viewNotificacao']}", $this->data);

//        return $this->subject($this->data['assunto'])
//            ->to($this->data['emails'])
//            ->attach($this->data['pagamentoPDF'], [
//                'as' => 'RECIBO PENDENTE'
//            ])->view("mail.{$this->data['viewNotificacao']}", $this->data);
    }
}
