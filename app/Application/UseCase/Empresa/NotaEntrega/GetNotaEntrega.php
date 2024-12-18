<?php

namespace App\Application\UseCase\Empresa\NotaEntrega;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\NotaEntregaRepository;

class GetNotaEntrega
{

    private NotaEntregaRepository $notaEntregaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->notaEntregaRepository = $repositoryFactory->createNotaEntregaRepository();
    }
    public function execute($faturaId){
        return $this->notaEntregaRepository->getNotaEntrega($faturaId);
    }

}
