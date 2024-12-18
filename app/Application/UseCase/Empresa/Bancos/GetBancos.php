<?php

namespace App\Application\UseCase\Empresa\Bancos;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\BancoRepository;

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
