<?php

namespace App\Application\UseCase\VendasOnline\Estoque;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\ExistenciaStockRepository;

class AtualizarExistenciaStock
{

    private ExistenciaStockRepository $existenciaStockRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->existenciaStockRepository = $repositoryFactory->createExistenciaStockRepository();
    }
    public function execute($produtoId, $quantidade, $armazemId, $operacao = "+"){
        return $this->existenciaStockRepository->atualizarStock($produtoId, $quantidade, $armazemId, $operacao);
    }

}
