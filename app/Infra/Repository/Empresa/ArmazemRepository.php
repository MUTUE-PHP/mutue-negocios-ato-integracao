<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Armazen as ArmazemDatabase;

class ArmazemRepository
{
    public function getArmazens(){
        return ArmazemDatabase::where('empresa_id', auth()->user()->empresa_id??53)
            ->get();
    }
    public function getArmazensVendasOnline(){
        return ArmazemDatabase::where('empresa_id', 148)
            ->get();
    }

}
