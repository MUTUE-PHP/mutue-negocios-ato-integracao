<?php

namespace App\Application\UseCase\Empresa\Produtos;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ProdutoRepository;

class GetProdutoPeloCentroCustoId
{
    private ProdutoRepository $produtoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->produtoRepository = $repositoryFactory->createProdutoRepository();
    }
    public function execute($centroCustoId){
        return $this->produtoRepository->getProdutoPeloCentroCustoId($centroCustoId);
    }

}
