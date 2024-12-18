<?php

namespace App\Application\UseCase\Empresa\DefinirSequenciaDocumentos;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\SequenciaDocumentoRepository;

class GetSequenciaDocumentos
{
    private SequenciaDocumentoRepository $sequenciaDocumentoRepository;


    public function __construct(RepositoryFactory $repositoryFactory){
        $this->sequenciaDocumentoRepository = $repositoryFactory->createSequenciaDocumentoRepository();
    }
    public function execute($search){
        return $this->sequenciaDocumentoRepository->findAll($search);
    }
}
