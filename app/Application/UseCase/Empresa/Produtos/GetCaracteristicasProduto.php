<?php

namespace App\Application\UseCase\Empresa\Produtos;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CarateristicaProdutoRepository;

class GetCaracteristicasProduto
{
    private CarateristicaProdutoRepository $carateristicaProdutoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->carateristicaProdutoRepository = $repositoryFactory->createCarateristicaRepository();
    }
    public function execute(){
        return $this->carateristicaProdutoRepository->gerCarateristicasProduto();
    }

}
