<?php

namespace App\Application\UseCase\Empresa\Inventarios;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\InventarioRepository;

class GetInventarios
{
    private InventarioRepository $inventarioRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->inventarioRepository = $repositoryFactory->createInventarioRepository();
    }
    public function execute($filtro = []){
        return $this->inventarioRepository->getInventarios($filtro);
    }

}
