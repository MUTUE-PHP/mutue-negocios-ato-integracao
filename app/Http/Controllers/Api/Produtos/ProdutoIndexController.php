<?php

namespace App\Http\Controllers\Api\Produtos;
use App\Application\UseCase\Empresa\Produtos\GetProdutosMaisVendidos;
use App\Application\UseCase\Empresa\Produtos\GetProdutosMaisVendidosTenancy;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassificacaoResource;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Produto;
use App\Repositories\Empresa\ProdutoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdutoIndexController extends Controller
{
    private $produtoRepository;

    public function __construct(ProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }
    public function quantidadeProdutos(){
        return $this->produtoRepository->quantidadeProdutos();
    }
    public function listarSeisProdutosMaisVendidos(){
        return $this->produtoRepository->listarSeisProdutosMaisVendidos();
    }
    public function getPodutoRelacionados($uuid){
        return $this->produtoRepository->listarProdutosRelacionais($uuid);
    }
    public function mv_listarProdutos(Request $request, $search = null){

        return $this->produtoRepository->mv_listarProdutos($request, $search);
    }
    public function listarProdutosPelaCategoria(Request $request, $categoriaId){
        return $this->produtoRepository->listarProdutosPelaCategoria($request, $categoriaId);
    }
    public function listarProdutosPelaCategoriaUuid(Request $request, $uuid){
        return $this->produtoRepository->listarProdutosPelaCategoriaUuid($request, $uuid);
    }
    public function mv_listarComentarioPorProduto($produtoId){
        $produto = $this->produtoRepository->mv_listarComentarioPorProduto($produtoId);
        return ClassificacaoResource::collection($produto);
    }
    public function listarProdutos($search = null)
    {
        return $this->produtoRepository->getProdutoComPaginacao($search);
    }
    public function listarProdutosMaisVendidos($search = null){
        $getProdutosMaisVendidos = new GetProdutosMaisVendidos(new DatabaseRepositoryFactory());
        return $getProdutosMaisVendidos->execute($search);
    }
    public function listarProdutosMaisVendidosTenancy($search = null){
        $getProdutosMaisVendidos = new GetProdutosMaisVendidosTenancy(new DatabaseRepositoryFactory());
        return $getProdutosMaisVendidos->execute($search);
    }

    public function getproduto($id)
    {
        return $this->produtoRepository->getproduto($id);
    }
    public function listarProdutosPeloIdArmazem($armazemId)
    {
        $centroCustoId = isset($_GET['centroCustoId'])?$_GET['centroCustoId']:null;
        $product_list = $this->produtoRepository->listarProdutosPeloIdArmazem($armazemId, $centroCustoId);
        foreach ($product_list as $produto) {
            foreach ($produto->existenciaEstock as $stock) {
                if ($stock->armazem_id == $armazemId) {
                    $produto->existenciaEstock = $stock;
                }
            }
        }
        return $product_list;
    }
}
