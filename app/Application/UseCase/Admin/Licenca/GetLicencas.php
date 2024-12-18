<?php

namespace App\Application\UseCase\Admin\Licenca;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\LicencaRepository;

class GetLicencas
{
    private LicencaRepository $licencaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->licencaRepository = $repositoryFactory->createLicencaRepository();
    }
    public function execute(){
        return $this->licencaRepository->getLicencas();
    }
}
