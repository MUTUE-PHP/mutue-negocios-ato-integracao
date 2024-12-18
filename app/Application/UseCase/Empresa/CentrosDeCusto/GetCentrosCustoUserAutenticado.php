<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CentroCustoRepository;

class GetCentrosCustoUserAutenticado
{
    private CentroCustoRepository $centroCustoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->centroCustoRepository = $repositoryFactory->createCentroCustoRepository();
    }
    public function execute(){
        return $this->centroCustoRepository->getCentrosCustoUserAutenticado();
    }

}
