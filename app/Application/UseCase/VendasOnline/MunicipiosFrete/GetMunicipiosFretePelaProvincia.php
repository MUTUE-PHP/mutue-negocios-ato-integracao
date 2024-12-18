<?php

namespace App\Application\UseCase\VendasOnline\MunicipiosFrete;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\MunicipiosFreteRepository;

class GetMunicipiosFretePelaProvincia
{

    private MunicipiosFreteRepository $municipiosFreteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->municipiosFreteRepository = $repositoryFactory->createMuniciposFreteRepository();
    }
    public function execute($provinciaId){
        return $this->municipiosFreteRepository->getMunicipiosFretePelaProvincia($provinciaId);
    }
}
