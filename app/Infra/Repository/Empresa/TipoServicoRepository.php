<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\TipoServico;

class TipoServicoRepository
{
    public function getTipoServicos()
    {
        return TipoServico::get();
    }

}
