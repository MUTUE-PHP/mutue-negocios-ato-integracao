<?php

namespace App\Application\UseCase\Admin\users;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\ClienteRepository;
use App\Infra\Repository\Admin\PermissaoRepository;
use App\Infra\Repository\Admin\RoleRepository;

class GetPermissoes
{
    private PermissaoRepository $permissaoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->permissaoRepository = $repositoryFactory->createPermissaoRepository();
    }
    public function execute(){
        return $this->permissaoRepository->listarPermissoes();
    }

}
