<?php

namespace App\Application\UseCase\Empresa\HistoricoCartaoCliente;
use App\Domain\Entity\Empresa\CartaoCliente\ExtratoCartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ExtratoCartaoClienteRepository;

class CadastrarHistoricoCartaoCliente
{

    private ExtratoCartaoClienteRepository $extratoCartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->extratoCartaoClienteRepository = $repositoryFactory->createExtratoCartaoClienteRepository();
    }
    public function execute($request){
        $historicoCartao = new ExtratoCartaoCliente(
            $request->clienteId,
            $request->bonus,
            $request->operacao,
            $request->saldo_anterior,
            $request->saldo_atual,
            $request->valorBonus,
            $request->valorDescontarCartao,
            $request->documetoReferente,
            $request->updateBonus
        );
        return $this->extratoCartaoClienteRepository->salvar($historicoCartao);

    }
}
