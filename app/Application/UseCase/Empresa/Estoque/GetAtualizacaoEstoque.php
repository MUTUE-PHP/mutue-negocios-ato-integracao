<?php

namespace App\Application\UseCase\Empresa\Estoque;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\AtualizarEstoqueRepository;

class GetAtualizacaoEstoque
{
    private AtualizarEstoqueRepository $atualizarEstoqueRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->atualizarEstoqueRepository = $repositoryFactory->createAtualizarEstoqueRepository();
    }
    public function execute($filtro){
        return $this->atualizarEstoqueRepository->getAtualizacoesEstoque($filtro);

    }

}
