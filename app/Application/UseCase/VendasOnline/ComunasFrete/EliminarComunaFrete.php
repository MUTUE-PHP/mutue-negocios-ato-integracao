<?php

namespace App\Application\UseCase\VendasOnline\ComunasFrete;
use App\Domain\Entity\VendasOnline\MunicipioFrete;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\ComunasFreteRepository;
use App\Infra\Repository\VendasOnline\MunicipiosFreteRepository;

class EliminarComunaFrete
{
    private ComunasFreteRepository $comunasFreteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->comunasFreteRepository = $repositoryFactory->createComunasFreteRepository();
    }
    public function execute($comunaId){
        return $this->comunasFreteRepository->delete($comunaId);
    }
}
