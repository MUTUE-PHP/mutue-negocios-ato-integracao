<?php

namespace App\Application\UseCase\Empresa\Pais;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\PaisRepository;

class GetPaises
{
    private PaisRepository $paisRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->paisRepository = $repositoryFactory->createPaisRepository();
    }
    public function execute(){
        return $this->paisRepository->getPaises()->toArray();
    }

}
