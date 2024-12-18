<?php

namespace App\Application\UseCase\Empresa\Faturas;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\FaturaRepository;

class GetFaturaPelaNumeracao
{
    private FaturaRepository $faturaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }
    public function execute($numeracaoDocumento){
        return $this->faturaRepository->getFaturaPelaNumeracao($numeracaoDocumento);
    }

}
