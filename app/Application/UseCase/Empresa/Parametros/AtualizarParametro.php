<?php

namespace App\Application\UseCase\Empresa\Parametros;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ParametroRepository;

class AtualizarParametro
{

    private ParametroRepository $parametroRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->parametroRepository = $repositoryFactory->createParametroRepository();
    }
    public function execute($parametroId, $valor, $label){
        return $this->parametroRepository->atualizarParametro($parametroId, $valor, $label);
    }

}
