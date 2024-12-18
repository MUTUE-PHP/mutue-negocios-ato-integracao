<?php

namespace App\Application\UseCase\Empresa\mercadorias;

use App\Domain\Entity\Empresa\Categoria;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CategoriaRepository;
use App\Infra\Repository\Empresa\TipoMercadoriaRepository;
use Illuminate\Http\Request;

class CadastrarTipoMercadoria
{

    private TipoMercadoriaRepository $tipoMercadoriaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->tipoMercadoriaRepository = $repositoryFactory->createTipoMercadoriaRepository();
    }
    public function execute(Request $request){
        $request = (object) $request;
        return $this->tipoMercadoriaRepository->salvar($request);
    }

}
