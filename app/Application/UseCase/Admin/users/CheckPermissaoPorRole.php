<?php

namespace App\Application\UseCase\Admin\users;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\PermissaoRepository;

class CheckPermissaoPorRole
{
    private PermissaoRepository $permissaoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->permissaoRepository = $repositoryFactory->createPermissaoRepository();
    }
    public function execute($permissaoId, $roleId){
        return $this->permissaoRepository->checkPermissaoPorRole($permissaoId, $roleId);
    }

}
