<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\TaxaCargaAduaneira;

class TaxaCargaAduaneiraRepository
{
    public function getTaxaCargaById($sujeitoDespachoId){
        return TaxaCargaAduaneira::where('id', $sujeitoDespachoId)->first();
    }

}
