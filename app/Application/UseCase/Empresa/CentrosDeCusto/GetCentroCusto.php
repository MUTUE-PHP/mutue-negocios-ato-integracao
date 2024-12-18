<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CentroCustoRepository;

class GetCentroCusto
{
    private CentroCustoRepository $centroCustoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->centroCustoRepository = $repositoryFactory->createCentroCustoRepository();
    }
    public function execute($centroCustoId){
        return $this->centroCustoRepository->getCentroCusto($centroCustoId);
    }
}
