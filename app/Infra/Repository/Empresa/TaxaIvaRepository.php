<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\TipoTaxa as TaxasIvaDatabase;

class TaxaIvaRepository
{
    public function getTaxasIvaDaEmpresaRegimeGeral(){
        return TaxasIvaDatabase::get();
    }
    public function getTaxasIvaDaEmpresaRegimeExclusaoESimplificado(){
        return TaxasIvaDatabase::where('codigo', 1)->get();
    }
}
