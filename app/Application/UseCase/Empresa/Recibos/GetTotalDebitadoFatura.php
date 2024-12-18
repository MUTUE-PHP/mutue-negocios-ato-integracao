<?php

namespace App\Application\UseCase\Empresa\Recibos;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\ReciboRepository;

class GetTotalDebitadoFatura
{
    private ReciboRepository $reciboRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->reciboRepository = $repositoryFactory->createReciboRepository();
    }
    public function execute($faturaId){
        return $this->reciboRepository->getTotalDebitadoFatura($faturaId);
    }

}
