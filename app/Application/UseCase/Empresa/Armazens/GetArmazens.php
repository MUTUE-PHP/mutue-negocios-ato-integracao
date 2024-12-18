<?php

namespace App\Application\UseCase\Empresa\Armazens;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;

class GetArmazens
{
    private ArmazemRepository $armazemRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->armazemRepository = $repositoryFactory->createArmazemRepository();
    }
    public function execute(){
        return $this->armazemRepository->getArmazens();
    }
}
