<?php

namespace App\Application\UseCase\Admin\Cliente;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\ClienteRepository;
use Illuminate\Http\Request;

class GetClientes
{
    private ClienteRepository $clienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
    }
    public function execute(){
        return $this->clienteRepository->getClientes();
    }

}
