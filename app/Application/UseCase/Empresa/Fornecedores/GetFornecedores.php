<?php

namespace App\Application\UseCase\Empresa\Fornecedores;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\FornecedorRepository;

class GetFornecedores
{
    private FornecedorRepository $fornecedorRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->fornecedorRepository = $repositoryFactory->createFornecedorRepository();
    }
    public function execute(){
        return $this->fornecedorRepository->getFornecedores();
    }
}
