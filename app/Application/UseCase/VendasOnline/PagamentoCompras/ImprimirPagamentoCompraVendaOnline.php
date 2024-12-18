<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\RelatorioVendaOnlineJasper;

class ImprimirPagamentoCompraVendaOnline
{
    private RelatorioVendaOnlineJasper $relatorioVendaOnlineJasper;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->relatorioVendaOnlineJasper = $repositoryFactory->createRelatorioVendaOnlineJasper();
    }
    public function execute($pagamentoId){
        return $this->relatorioVendaOnlineJasper->imprimirPagamentoVendaOnline2($pagamentoId);
    }

}
