<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\HistoricoPagamentoRepository;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;

class ConfirmarEntrega
{
    public PagamentoVendasOnlineRepository $pagamentoRepository;
    public HistoricoPagamentoRepository $historicoPagamentosVendasOnline;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->pagamentoRepository = $repositoryFactory->createPagamentoVendaOnlineRepository();
        $this->historicoPagamentosVendasOnline = $repositoryFactory->createHistoricoPagamentoRepository();
    }
    public function execute($pagamentoId){
        $pagamento = $this->pagamentoRepository->get($pagamentoId);
        if(!$pagamento) throw new \Error("Pagamento não encontrado");
        $output =  $this->pagamentoRepository->confirmarEntregaProdutoVendaOnline($pagamentoId);
        $ENTREGUE = 5;
        $descricao ="O operador ".auth()->user()->name. " confirmou a entrega do pagamento N.º ". $pagamento['codigo'];
        $this->historicoPagamentosVendasOnline->atualizarHistoricoPagamento($pagamentoId, $ENTREGUE, $descricao);
        return $output;
    }

}
