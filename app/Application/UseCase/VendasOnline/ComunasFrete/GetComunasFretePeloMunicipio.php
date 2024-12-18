<?php

namespace App\Application\UseCase\VendasOnline\ComunasFrete;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\ComunasFreteRepository;

class GetComunasFretePeloMunicipio
{

    private ComunasFreteRepository $comunasFreteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->comunasFreteRepository = $repositoryFactory->createComunasFreteRepository();
    }
    public function execute($municipioId){
        return $this->comunasFreteRepository->getComunasFretePelMunicipio($municipioId);
    }
}
