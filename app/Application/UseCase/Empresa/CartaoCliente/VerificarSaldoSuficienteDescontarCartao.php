<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;

use App\Domain\Entity\Empresa\CartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;

class VerificarSaldoSuficienteDescontarCartao
{

    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute($numeroCartao, $valorDescontar){
        $getCartaoCliente = new GetCartaoClientePeloNumero(new DatabaseRepositoryFactory());
        $cartaoCliente = $getCartaoCliente->execute($numeroCartao);
        if(!$cartaoCliente) throw  new \Error("Cartão não encontrado");
        $isValid = $valorDescontar <= $cartaoCliente->saldo;
       return $isValid;
    }

}
