<?php

namespace App\Application\UseCase\VendasOnline\ValidacaoPagamento;
use App\Domain\Entity\VendasOnline\FaturaReciboVendaOnline;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\UserRepository;
use App\Mail\NotificacaoPagamentoVendaOnline;
use App\Mail\NotificacaoRejeicaoPagamentoVendaOnline;
use App\Mail\NotificacaoValidadoPagamentoVendaOnline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PagamentoValidado
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
        $emitirFaturaRecibo = new EmitirFaturaReciboVendaOnline(new DatabaseRepositoryFactory());
        $fatura = $emitirFaturaRecibo->execute($pagamento);
        $pagamentoData = $this->pagamentoVendasOnlineRepository->validarPagamento($pagamento);
        if($pagamentoData){
            $data['emails'] = $this->userAdminRepository->getEmailsAdminParaNotificar();
            $data['codigo'] = $pagamento->codigo;
            array_push($data['emails'], $pagamento['emailEntrega']);
            $data['assunto'] = 'Pagamento Aceite: ' . $pagamento->codigo;
            try{
                Mail::send(new NotificacaoValidadoPagamentoVendaOnline($data));
            }catch (\Exception $e){
                Log::error($e->getMessage());
            }
        }
        return $fatura;
    }

}
