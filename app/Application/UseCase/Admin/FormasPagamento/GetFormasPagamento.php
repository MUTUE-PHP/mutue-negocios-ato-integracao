<?php

namespace App\Application\UseCase\Admin\FormasPagamento;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\FormaPagamentoRepository;

class GetFormasPagamento
{
    private FormaPagamentoRepository $formaPagamentoRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->formaPagamentoRepository = $repositoryFactory->createFormaPagamentoRepository();
    }
    public function execute(){
        return $this->formaPagamentoRepository->getFormasPagamentos();
    }
}
