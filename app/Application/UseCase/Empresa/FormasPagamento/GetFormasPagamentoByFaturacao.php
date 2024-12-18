<?php

namespace App\Application\UseCase\Empresa\FormasPagamento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\FormasPagamentoRepository;

class GetFormasPagamentoByFaturacao
{
    private FormasPagamentoRepository $formasPagamentoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->formasPagamentoRepository = $repositoryFactory->createFormasPagamentoRepository();
    }
    public function execute(){
        return $this->formasPagamentoRepository->getFormasPagamentosByFaturacao();
    }

}
