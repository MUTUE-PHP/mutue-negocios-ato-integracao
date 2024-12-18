<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarCodigoRecuperacaoSenhaVendaOnline extends Mailable
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
        $this->to($this->data['email']);
        $this->subject('Bem-vindo ao Mutue vendas online! Código para recuperação de senha');
        return $this->view("mail.CodigoRecuperacaoSenhaUtilizador", $this->data);
    }


}
