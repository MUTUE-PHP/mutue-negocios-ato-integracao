<?php

namespace App\Application\UseCase\Admin\users;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\ClienteRepository;
use App\Infra\Repository\Admin\RoleRepository;

class GetPerfisUtilizadores
{
    private RoleRepository $roleRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->roleRepository = $repositoryFactory->createRoleRepository();
    }
    public function execute(){
        return $this->roleRepository->listarPerfils();
    }

}
