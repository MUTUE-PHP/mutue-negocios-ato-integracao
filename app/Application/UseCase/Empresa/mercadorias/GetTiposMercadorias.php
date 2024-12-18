<?php

namespace App\Application\UseCase\Empresa\mercadorias;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\TipoMercadoriaRepository;

class GetTiposMercadorias
{
    private TipoMercadoriaRepository $tipoMercadoriaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->tipoMercadoriaRepository = $repositoryFactory->createTipoMercadoriaRepository();
    }
    public function execute()
    {
        return $this->tipoMercadoriaRepository->getTipoMercadorias();
    }
}
