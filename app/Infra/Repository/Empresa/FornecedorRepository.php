<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Fornecedor as FornecedorDatabase;

class FornecedorRepository
{
    public function getFornecedores(){
        return FornecedorDatabase::where('empresa_id', auth()->user()->empresa_id??53)
            ->get();
    }
    public function getFornecedoresSemDiversos(){
        return FornecedorDatabase::where('empresa_id', auth()->user()->empresa_id??53)
            ->where('diversos', 2)
            ->get();
    }
}
