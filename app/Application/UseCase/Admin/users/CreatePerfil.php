<?php

namespace App\Application\UseCase\Admin\users;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\RoleRepository;
use Illuminate\Http\Request;

class CreatePerfil
{
    private RoleRepository $roleRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->roleRepository = $repositoryFactory->createRoleRepository();
    }
    public function execute($data){
        return $this->roleRepository->store($data);
    }

}
