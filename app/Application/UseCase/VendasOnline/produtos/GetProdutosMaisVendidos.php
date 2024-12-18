<?php

namespace App\Application\UseCase\VendasOnline\produtos;


use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ProdutoRepository;

class GetProdutosMaisVendidos
{
    private ProdutoRepository $produtoRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->produtoRepository = $repositoryFactory->createProdutoRepository();
    }
    public function execute($search = null){

        return $this->produtoRepository->getProdutosMaisVendidos($search);
    }

}
