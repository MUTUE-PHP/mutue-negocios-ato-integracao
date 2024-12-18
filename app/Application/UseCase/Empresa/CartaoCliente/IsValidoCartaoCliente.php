<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;

use App\Domain\Entity\Empresa\CartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;

class IsValidoCartaoCliente
{

    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute($numeroCartao){
        $cartao = $this->cartaoClienteRepository->getCartaoClientePeloNumero($numeroCartao);
        if(!$cartao) return false;
        $cartao = new CartaoCliente(
            $cartao->clienteId,
            $cartao->saldo,
            $cartao->numeroCartao,
            $cartao->dataEmissao,
            $cartao->dataValidade,
            $cartao->numeracaoSequencia,
        );
        return $cartao->isValid();
    }

}
