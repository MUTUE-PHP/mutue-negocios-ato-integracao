<?php

namespace App\Application\UseCase\Empresa\Produtos;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\OrderByProdutoRepository;

class GetOrderByProdutos
{

    private OrderByProdutoRepository $orderByProdutoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->orderByProdutoRepository = $repositoryFactory->createOrderByProdutoRepository();
    }
    public function execute(){
        return $this->orderByProdutoRepository->getOrderByProduto();
    }

}
