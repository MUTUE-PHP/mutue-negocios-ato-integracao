<?php

namespace App\Application\UseCase\Empresa\Users;

use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\UserRepository;

class GetUserPeloUuid
{
    private UserRepository $armazemRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->armazemRepository = $repositoryFactory->createUserRepositoryEmpresa();
    }
    public function execute($uuid){
        return $this->armazemRepository->getUserPeloUuid($uuid);
    }

}
