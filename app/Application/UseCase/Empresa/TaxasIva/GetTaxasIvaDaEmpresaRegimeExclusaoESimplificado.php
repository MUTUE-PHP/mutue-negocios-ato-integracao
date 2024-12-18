<?php

namespace App\Application\UseCase\Empresa\TaxasIva;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\TaxaIvaRepository;

class GetTaxasIvaDaEmpresaRegimeExclusaoESimplificado
{
    private TaxaIvaRepository $taxaIvaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->taxaIvaRepository = $repositoryFactory->createTaxaIvaRepository();
    }
    public function execute(){
        return $this->taxaIvaRepository->getTaxasIvaDaEmpresaRegimeExclusaoESimplificado();
    }

}
