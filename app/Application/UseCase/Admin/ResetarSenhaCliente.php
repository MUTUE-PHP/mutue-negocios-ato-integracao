<?php

namespace App\Application\UseCase\Admin;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\ClienteRepository;
use Illuminate\Http\Request;

class ResetarSenhaCliente
{
    private ClienteRepository $clienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
    }
    public function execute(Request $request){

        $cliente = $this->clienteRepository->getCliente($request->clienteId);
        if(!$cliente){
            throw new \Exception("Cliente não encontrado");
        }
        if(!$request->novaSenha){
            throw new \Exception("Informe a nova senha");
        }
        return $this->clienteRepository->resetarSenhaDoCliente($cliente, $request->novaSenha);
    }
}
