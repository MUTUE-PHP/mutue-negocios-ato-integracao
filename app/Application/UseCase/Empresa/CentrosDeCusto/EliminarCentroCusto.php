<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use App\Domain\Entity\Empresa\CentrosDeCusto\CentroCusto;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CentroCustoRepository;
use Illuminate\Http\Request;

class EliminarCentroCusto
{

    use TraitUploadFileDocEmpresa;
    use TraitUploadFileLogotipoEmpresa;

    private CentroCustoRepository $centroCustoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->centroCustoRepository = $repositoryFactory->createCentroCustoRepository();
    }
    public function execute($centroCustoId)
    {
        return $this->centroCustoRepository->delete($centroCustoId);
    }
}
