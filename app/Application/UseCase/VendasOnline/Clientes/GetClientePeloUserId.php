<?php

namespace App\Application\UseCase\VendasOnline\Clientes;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\ClienteRepository;

class GetClientePeloUserId
{
    private ClienteRepository $clienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
    }
    public function execute($userId){

        return $this->clienteRepository->getClientePeloUserId($userId);
    }

}
