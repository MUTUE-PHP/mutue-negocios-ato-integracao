<?php

namespace App\Application\UseCase\Empresa\Clientes;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\ClienteRepository;

class GetClientes
{
    private ClienteRepository $clienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
    }
    public function execute($search = null){
        return $this->clienteRepository->getClientes($search);
    }
}
