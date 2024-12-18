<?php

namespace App\Application\UseCase\Empresa\CartaoCliente;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;
use Illuminate\Http\Request;

class AtualizarBonusCartaoCliente
{
    private CartaoClienteRepository $cartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
    }
    public function execute(Request $request){
        return $this->cartaoClienteRepository->atualizarBonusCartaoCliente($request);
    }


}
