<?php

namespace App\Application\UseCase\Empresa\NotaEntrega;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\ClienteRepository;
use App\Infra\Repository\Empresa\ParametroRepository;

class GetHabilitadoNotaEntrega
{
    private ParametroRepository $parametroRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->parametroRepository = $repositoryFactory->createParametroRepository();
    }
    public function execute(){
        return $this->parametroRepository->getHabilitadoNotaEntrega();
    }
}
