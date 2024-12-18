<?php

namespace App\Application\UseCase\Empresa\TiposServicos;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\TipoServicoRepository;

class GetTiposServicos
{

    private TipoServicoRepository $tipoServicoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->tipoServicoRepository = $repositoryFactory->createTipoServicoRepository();
    }
    public function execute(){
        return $this->tipoServicoRepository->getTipoServicos();
    }

}
