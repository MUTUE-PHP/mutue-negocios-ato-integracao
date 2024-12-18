<?php

namespace App\Application\UseCase\Empresa\Produtos;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\AtualizacaoStockRepository;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use App\Infra\Repository\Empresa\ProdutoRepository;
use App\Infra\Repository\VendasOnline\CarrinhoVendasOnlineRepository;
use Illuminate\Http\Request;

class EliminarProduto
{
    private ProdutoRepository $produtoRepository;
    private $carrinhoVendasOnlineRepository;
    private ExistenciaStockRepository $existenciaStockRepository;
    private AtualizacaoStockRepository $atualizacaoStockRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->produtoRepository = $repositoryFactory->createProdutoRepository();
        $this->existenciaStockRepository = $repositoryFactory->createExistenciaStockRepository();
        $this->atualizacaoStockRepository = $repositoryFactory->createAtualizacaoStockRepository();
        $this->carrinhoVendasOnlineRepository = $repositoryFactory->createCarrinhoRepository();
    }

    public function execute($produtoId)
    {
        $this->existenciaStockRepository->eliminarProduto($produtoId);
        $this->atualizacaoStockRepository->eliminarProduto($produtoId);
        $this->carrinhoVendasOnlineRepository->eliminarProduto($produtoId);
        $this->produtoRepository->eliminarProduto($produtoId);
    }

}
