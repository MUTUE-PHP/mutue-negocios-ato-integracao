<?php

namespace App\Application\UseCase\VendasOnline\MunicipiosFrete;
use App\Domain\Entity\VendasOnline\MunicipioFrete;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\MunicipiosFreteRepository;

class AtualizarMunicipioFrete
{
    private MunicipiosFreteRepository $municipiosFreteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->municipiosFreteRepository = $repositoryFactory->createMuniciposFreteRepository();
    }
    public function execute($request, $municipioId){
        $municipioFrete = new MunicipioFrete(
            $request->designacao,
            $request->valorEntrega,
            $request->provinciaId,
            $request->statusId
        );
        return $this->municipiosFreteRepository->atualizar($municipioFrete , $municipioId);
    }
}
