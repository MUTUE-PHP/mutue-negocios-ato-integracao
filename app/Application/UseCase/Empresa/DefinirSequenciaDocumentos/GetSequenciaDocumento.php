<?php

namespace App\Application\UseCase\Empresa\DefinirSequenciaDocumentos;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\SequenciaDocumentoRepository;

class GetSequenciaDocumento
{
    private SequenciaDocumentoRepository $sequenciaDocumentoRepository;


    public function __construct(RepositoryFactory $repositoryFactory){
        $this->sequenciaDocumentoRepository = $repositoryFactory->createSequenciaDocumentoRepository();
    }
    public function execute($id){
        return $this->sequenciaDocumentoRepository->getSequenciaDocumento($id);
    }
}
