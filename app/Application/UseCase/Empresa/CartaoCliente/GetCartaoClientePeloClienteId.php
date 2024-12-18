<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;

class GetCartaoClientePeloClienteId
{
    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute($clienteId){
        return $this->cartaoClienteRepository->getCartaoClientePeloClienteId($clienteId);
    }
}
