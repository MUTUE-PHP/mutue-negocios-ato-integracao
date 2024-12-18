<?php

namespace App\Application\UseCase\Empresa\Categorias;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CategoriaRepository;

class GetCategoriasMaeESubs
{
    private CategoriaRepository $categoriaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->categoriaRepository = $repositoryFactory->createCategoriaRepository();
    }
    public function execute(){
        return $this->categoriaRepository->getCategoriaMaeESubs();
    }
}
