<?php
namespace App\Application\UseCase\Admin\Licenca;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\PagamentoLicencaRepository;

class GetPagamento{

    private PagamentoLicencaRepository $pagamentoLicencaRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->pagamentoLicencaRepository = $repositoryFactory->createPagamentoLicencaRepository();
    }
    public function execute($pagamentoId){
        return $this->pagamentoLicencaRepository->getPagamento($pagamentoId);
    }
}
