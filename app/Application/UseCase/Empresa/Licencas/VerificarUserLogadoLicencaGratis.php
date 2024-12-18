<?php

namespace App\Application\UseCase\Empresa\Licencas;
use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\AtivacaoLicencaRepository;

class VerificarUserLogadoLicencaGratis
{

    private AtivacaoLicencaRepository $ativacaoLicencaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->ativacaoLicencaRepository = $repositoryFactory->createAtivacaoLicencaRepository();
    }
    public function execute(){
        return $this->ativacaoLicencaRepository->verificarUltimaLicencaGratis();
    }

}
