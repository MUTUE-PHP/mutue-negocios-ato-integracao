<?php

namespace App\Application\UseCase\Empresa\UnidadesMedida;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\CategoriaRepository;
use App\Infra\Repository\Empresa\UnidadesMedidaRepository;

class GetUnidadesMedida
{
    private UnidadesMedidaRepository $unidadesMedidaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->unidadesMedidaRepository = $repositoryFactory->createUnidadesMedidaRepository();
    }
    public function execute(){
        return $this->unidadesMedidaRepository->getUnidadesMedida();
    }
}
