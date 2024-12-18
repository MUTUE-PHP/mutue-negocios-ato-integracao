<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\UnidadeMedida as UnidadeMedidaDatabase;

class UnidadesMedidaRepository
{
    public function getUnidadesMedida(){
        return UnidadeMedidaDatabase::where('empresa_id', auth()->user()->empresa_id??53)
            ->get();
    }
}
