<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;

class EliminarBonusCartaoCliente
{
    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute($bonusId){
        return $this->cartaoClienteRepository->eliminarBonusCartaoCliente($bonusId);
    }
}
