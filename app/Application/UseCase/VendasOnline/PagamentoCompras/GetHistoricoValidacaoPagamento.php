<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\HistoricoPagamentoRepository;

class GetHistoricoValidacaoPagamento
{
    private HistoricoPagamentoRepository $historicoPagamentoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->historicoPagamentoRepository = $repositoryFactory->createHistoricoPagamentoRepository();
    }

    public function execute()
    {
        return $this->historicoPagamentoRepository->getHistoricoPagamentoOnline();
    }

}
