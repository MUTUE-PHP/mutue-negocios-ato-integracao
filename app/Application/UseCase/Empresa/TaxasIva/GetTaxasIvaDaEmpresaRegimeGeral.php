<?php

namespace App\Application\UseCase\Empresa\TaxasIva;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\CategoriaRepository;
use App\Infra\Repository\Empresa\TaxaIvaRepository;

class GetTaxasIvaDaEmpresaRegimeGeral
{
    private TaxaIvaRepository $taxaIvaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->taxaIvaRepository = $repositoryFactory->createTaxaIvaRepository();
    }
    public function execute(){
        return $this->taxaIvaRepository->getTaxasIvaDaEmpresaRegimeGeral();
    }
}
