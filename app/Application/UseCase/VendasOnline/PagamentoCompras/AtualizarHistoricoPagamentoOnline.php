<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\HistoricoPagamentoRepository;

class AtualizarHistoricoPagamentoOnline
{
    private HistoricoPagamentoRepository $historicoPagamentoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->historicoPagamentoRepository = $repositoryFactory->createHistoricoPagamentoRepository();
    }

    public function execute($pagamentoId, $statusPagamentoId, $descricao = null)
    {
        return $this->historicoPagamentoRepository->atualizarHistoricoPagamento($pagamentoId, $statusPagamentoId, $descricao);
    }

}
