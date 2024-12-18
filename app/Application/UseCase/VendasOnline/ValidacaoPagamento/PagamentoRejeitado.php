<?php

namespace App\Application\UseCase\VendasOnline\ValidacaoPagamento;
use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\VendasOnline\Estoque\AtualizarExistenciaStock;
use App\Application\UseCase\VendasOnline\PagamentoCompras\AtualizarHistoricoPagamentoOnline;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\UserRepository;
use App\Mail\NotificacaoPagamentoVendaOnline;
use App\Mail\NotificacaoRejeicaoPagamentoVendaOnline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PagamentoRejeitado
{

    private PagamentoVendasOnlineRepository $pagamentoVendasOnlineRepository;
    private UserRepository $userAdminRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->pagamentoVendasOnlineRepository = $repositoryFactory->createPagamentoVendaOnlineRepository();
        $this->userAdminRepository = $repositoryFactory->createUserRepository();

    }

    public function execute($pagamento)
    {
        $pagamentoData = $this->pagamentoVendasOnlineRepository->rejeitarPagamento($pagamento);
        $getArmazem = new GetArmazens(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
        $armazens = $getArmazem->execute();
        $armazemId = $armazens[0]['id'];
        $operacao = "+";
        foreach ($pagamento['pagamentoVendasOnlineItems'] as $item){
            $atualizarStock = new AtualizarExistenciaStock(new DatabaseRepositoryFactory());
            $atualizarStock->execute($item['produtoId'], $item['quantidade'], $armazemId, $operacao);
        }
        if($pagamentoData){
            $REJEITADO = 3;
            $descricao = "Pagamento rejeitado pelo operador ".Str::title(auth()->user()->name)." pelo seguinte motivo: ".$pagamento->motivoRejeicao;
            $atualizarHistoricoPagamento = new AtualizarHistoricoPagamentoOnline(new DatabaseRepositoryFactory());
            $atualizarHistoricoPagamento->execute($pagamento->id, $REJEITADO, $descricao);
            $data['emails'] = $this->userAdminRepository->getEmailsAdminParaNotificar();
            $data['codigo'] = $pagamento->codigo;
            $data['motivoRejeicao'] = $pagamento->motivoRejeicao;
            array_push($data['emails'], $pagamento['emailEntrega']);
            $data['assunto'] = 'Pagamento rejeitado: ' . $pagamento->codigo;
            try {
                Mail::send(new NotificacaoRejeicaoPagamentoVendaOnline($data));
            }catch (\Exception $ex){
                Log::error($ex->getMessage());
            }
        }
        return $pagamento;
    }

}
