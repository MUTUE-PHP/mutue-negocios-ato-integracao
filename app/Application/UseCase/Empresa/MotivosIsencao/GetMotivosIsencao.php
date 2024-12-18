<?php

namespace App\Application\UseCase\Empresa\MotivosIsencao;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\CategoriaRepository;
use App\Infra\Repository\Empresa\MotivosIsencaoRepository;

class GetMotivosIsencao
{
    private MotivosIsencaoRepository $motivosIsencaoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->motivosIsencaoRepository = $repositoryFactory->createMotivoIsencaoRepository();
    }
    public function execute(){
        return $this->motivosIsencaoRepository->getMotivosIsencao();
    }
}
