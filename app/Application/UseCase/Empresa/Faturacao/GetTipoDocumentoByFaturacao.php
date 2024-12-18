<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ClienteRepository;
use App\Infra\Repository\Empresa\TipoDocumentoRepository;

class GetTipoDocumentoByFaturacao
{
    private TipoDocumentoRepository $tipoDocumentoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->tipoDocumentoRepository = $repositoryFactory->createTipoDocumentoRepository();
    }
    public function execute(){
        return $this->tipoDocumentoRepository->getTipoDocumentoFaturacao();
    }

}
