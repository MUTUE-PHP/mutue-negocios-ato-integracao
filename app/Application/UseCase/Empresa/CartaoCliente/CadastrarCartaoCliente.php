<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;

use App\Domain\Entity\Empresa\CartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;
use Illuminate\Support\Facades\Validator;

class CadastrarCartaoCliente
{
    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute($request){

        $request = (object) $request;
        $numeracaoSequencia = $this->cartaoClienteRepository->getUltimaNumeracaoSequenciaCartaoCliente();
        $numeroCartao = $this->gerarNumeroCartaoCliente($numeracaoSequencia);
        $cartaoCliente = new CartaoCliente(
            $request->clienteId,
            $request->saldo ?? 0,
            $numeroCartao,
            $request->dataEmissao,
            $request->dataValidade,
            $numeracaoSequencia
        );
        $cartao = $this->cartaoClienteRepository->salvar($cartaoCliente);
        return $cartao;
    }
    private function gerarNumeroCartaoCliente($numeracaoSequencia){
        $empresaAuth = auth()->user()->empresa_id??35;
        return $empresaAuth.''.str_pad($numeracaoSequencia, 5, '0', STR_PAD_LEFT);
    }
}
