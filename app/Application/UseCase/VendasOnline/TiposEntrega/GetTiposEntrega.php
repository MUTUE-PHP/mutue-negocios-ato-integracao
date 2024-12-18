<?php

namespace App\Application\UseCase\VendasOnline\TiposEntrega;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\TipoEntregaRepository;

class GetTiposEntrega
{
    private TipoEntregaRepository $tipoEntregaRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->tipoEntregaRepository = $repositoryFactory->createTipoEntregaRepository();
    }
    public function execute(){
        return $this->tipoEntregaRepository->getTiposEntrega();
    }
}
