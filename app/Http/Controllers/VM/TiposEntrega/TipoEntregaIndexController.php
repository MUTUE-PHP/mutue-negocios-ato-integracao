<?php

namespace App\Http\Controllers\VM\TiposEntrega;

use App\Application\UseCase\VendasOnline\TiposEntrega\GetTiposEntrega;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;

class TipoEntregaIndexController extends Controller
{
    public function listarTiposEntregas(){
        $getTiposEntregas = new GetTiposEntrega(new DatabaseRepositoryFactory());
        return $getTiposEntregas->execute();
    }

}
