<?php

namespace App\Application\UseCase\Admin\Anuncios;
use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\AnuncioRepository;

class GetAnunciosPorDataValidas
{
    private AnuncioRepository $anuncioRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->anuncioRepository = $repositoryFactory->createAnuncioRepository();
    }
    public function execute(){
        return $this->anuncioRepository->getAnunciosPorDataValidas();
    }

}
