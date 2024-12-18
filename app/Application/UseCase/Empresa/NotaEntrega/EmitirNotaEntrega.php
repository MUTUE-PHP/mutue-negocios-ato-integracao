<?php

namespace App\Application\UseCase\Empresa\NotaEntrega;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\NotaEntregaRepository;

class EmitirNotaEntrega
{

    private NotaEntregaRepository $notaEntregaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->notaEntregaRepository = $repositoryFactory->createNotaEntregaRepository();
    }
    public function execute($fatura){

        return $this->notaEntregaRepository->emitirNotaEntrega($fatura);
    }


}
