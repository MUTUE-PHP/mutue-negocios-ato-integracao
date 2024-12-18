<?php

namespace App\Application\UseCase\Empresa\Categorias;

use App\Domain\Entity\Empresa\Categoria;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ArmazemRepository;
use App\Infra\Repository\Empresa\CategoriaRepository;
use Illuminate\Http\Request;

class CadastrarCategoria
{
    private CategoriaRepository $categoriaRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->categoriaRepository = $repositoryFactory->createCategoriaRepository();
    }
    public function execute(Request $request){
        $request = (object) $request;
        $categoria = new Categoria(
            $request->designacao,
            $request->categoria_pai,
            $request->status_id
        );
        return $this->categoriaRepository->salvar($categoria);
    }
}
