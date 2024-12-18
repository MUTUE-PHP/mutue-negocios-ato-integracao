<?php

namespace App\Application\UseCase\Empresa\mercadorias;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\TipoMercadoriaRepository;

class GetTipoMercadoria
{
    private TipoMercadoriaRepository $tipoMercadoriaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->tipoMercadoriaRepository = $repositoryFactory->createTipoMercadoriaRepository();
    }
    public function execute($mercadoriaId)
    {
        return $this->tipoMercadoriaRepository->getTipoMercadoria($mercadoriaId);
    }
}
