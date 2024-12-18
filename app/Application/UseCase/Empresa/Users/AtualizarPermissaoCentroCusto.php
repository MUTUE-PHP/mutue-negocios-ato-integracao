<?php

namespace App\Application\UseCase\Empresa\Users;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\UserRepository;

class AtualizarPermissaoCentroCusto
{
    private UserRepository $userRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->userRepository = $repositoryFactory->createUserRepositoryEmpresa();
    }
    public function execute($centroCentroId, $userId){
        return $this->userRepository->atualizarPermissaoCentroCusto($centroCentroId, $userId);
    }
}
