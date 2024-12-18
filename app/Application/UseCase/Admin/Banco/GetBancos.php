<?php

namespace App\Application\UseCase\Admin\Banco;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\BancoRepository;

class GetBancos
{
    private BancoRepository $bancoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->bancoRepository = $repositoryFactory->createBancoRepository();
    }
    public function execute(){
        return $this->bancoRepository->getBancos();
    }

}
