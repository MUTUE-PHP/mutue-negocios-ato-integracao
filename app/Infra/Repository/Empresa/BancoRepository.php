<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Banco;

class BancoRepository
{

    public function getBancos()
    {
        return Banco::where('empresa_id', auth()->user()->empresa_id)
            ->where('status_id', 1)
            ->get();
    }

}
