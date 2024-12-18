<?php

namespace App\Application\UseCase\Empresa\CartaGarantia;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\ClienteRepository;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\ParametroRepository;

class GetHabilitadoCartaGarantia
{
    private FaturaRepository $faturaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }
    public function execute($faturaId){
        return $this->faturaRepository->getHabilitadoCartaGarantia($faturaId);
    }
}
