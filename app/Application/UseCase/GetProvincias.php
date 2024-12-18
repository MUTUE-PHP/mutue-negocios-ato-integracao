<?php

namespace App\Application\UseCase;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ProvinciaRepository;

class GetProvincias
{
    private ProvinciaRepository $provinciaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->provinciaRepository = $repositoryFactory->createProvinciaRepository();
    }
    public function execute(){
        return $this->provinciaRepository->getProvincias();
    }
}
