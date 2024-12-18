<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;

class AtualizarPagamento
{
    private PagamentoVendasOnlineRepository $pagamentoVendasOnlineRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->pagamentoVendasOnlineRepository = $repositoryFactory->createPagamentoVendaOnlineRepository();
    }

    public function execute($pagamentoId, $statusPagamentoId, $comprovativoBancario)
    {
        return $this->pagamentoVendasOnlineRepository->atualizarPagamento($pagamentoId, $statusPagamentoId, $comprovativoBancario);
    }

}
