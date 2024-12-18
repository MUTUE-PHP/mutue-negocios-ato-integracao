<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;

use App\Domain\Entity\Empresa\CartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;
use Illuminate\Support\Facades\Validator;

class AtualizarCartaoCliente
{
    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute($request){
        $request = (object) $request;
        $cartaoCliente = new CartaoCliente(
            $request->clienteId,
            $request->saldo ?? 0,
            $request->numeroCartao,
            $request->dataEmissao,
            $request->dataValidade,
            $request->numeracaoSequencia,
        );
        $cartao = $this->cartaoClienteRepository->atualizarCartaoCliente($cartaoCliente, $request->id);
        return $cartao;
    }
}
