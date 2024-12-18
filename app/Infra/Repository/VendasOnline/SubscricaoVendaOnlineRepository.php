<?php

namespace App\Infra\Repository\VendasOnline;
use App\Models\empresa\SubscricaoVendaOnline;

class SubscricaoVendaOnlineRepository
{
    public function salvar($email)
    {
        return SubscricaoVendaOnline::create([
            'email' => $email,
            'estado_recebimento' => 'ACTIVO',
        ]);
    }
    public function delete($email){
        return SubscricaoVendaOnline::where('email', $email)
            ->delete();
    }
}
