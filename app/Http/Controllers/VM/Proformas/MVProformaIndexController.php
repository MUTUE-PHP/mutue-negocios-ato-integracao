<?php

namespace App\Http\Controllers\VM\Proformas;

use App\Application\UseCase\VendasOnline\Proformas\ImprimiProformaVendasOnline;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Models\empresa\Provincia;

class MVProformaIndexController extends Controller
{
    public function imprimirProformaVendasOnline(){
        $proforma = new ImprimiProformaVendasOnline(new DatabaseRepositoryFactory());
        return $proforma->execute();
    }
}
