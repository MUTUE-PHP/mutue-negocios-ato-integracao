<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Cliente as ClienteDatabase;

class ClienteRepository
{
    public function getClientes($search = null){
        return ClienteDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('id', '!=', 1)
            ->search(trim($search))
            ->orderBy('nome', 'asc')
            ->get();
    }
    public function getClientesSemConsumidorFinal(){
        return ClienteDatabase::where('empresa_id', auth()->user()->empresa_id??53)
            ->where('diversos', 'Não')
            ->get();
    }

}
