<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Fabricante as FabricanteDatabase;

class FabricanteRepository
{
    public function getFabricantes(){
        return FabricanteDatabase::where('empresa_id', auth()->user()->empresa_id??53)
            ->get();
    }
}
