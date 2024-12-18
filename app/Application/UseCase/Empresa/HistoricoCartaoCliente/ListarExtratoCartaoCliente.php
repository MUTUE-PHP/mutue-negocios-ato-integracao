<?php

namespace App\Application\UseCase\Empresa\HistoricoCartaoCliente;

use App\Domain\Entity\Empresa\CartaoCliente\ExtratoCartaoCliente;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ExtratoCartaoClienteRepository;

class ListarExtratoCartaoCliente
{
    private ExtratoCartaoClienteRepository $extratoCartaoClienteRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->extratoCartaoClienteRepository = $repositoryFactory->createExtratoCartaoClienteRepository();
    }
    public function execute($numeroCartao){
        return $this->extratoCartaoClienteRepository->listarExtratoCartaoCliente($numeroCartao);
    }

}
