<?php

namespace App\Application\UseCase\VendasOnline\Proformas;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\RelatorioVendaOnlineJasper;
class ImprimiProformaVendasOnline
{
    private RelatorioVendaOnlineJasper $relatorioVendaOnlineJasper;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->relatorioVendaOnlineJasper = $repositoryFactory->createRelatorioVendaOnlineJasper();
    }
    public function execute(){
        return $this->relatorioVendaOnlineJasper->imprimirProformaCarrinho();
    }
}
