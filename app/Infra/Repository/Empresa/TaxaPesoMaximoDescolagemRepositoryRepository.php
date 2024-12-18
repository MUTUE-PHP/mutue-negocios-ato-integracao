<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\ExistenciaStock as ExistenciaStockDatabase;
use Illuminate\Support\Facades\DB;

class TaxaPesoMaximoDescolagemRepositoryRepository
{

    public function getTaxaPmd($pmd){
        $taxa = DB::connection('mysql2')->table('intervalo_pmd')
            ->where('toneladas_min', '<=', $pmd)
            ->where('toneladas_max', '>=', $pmd)
            ->first();
        return $taxa??0;

    }

}
