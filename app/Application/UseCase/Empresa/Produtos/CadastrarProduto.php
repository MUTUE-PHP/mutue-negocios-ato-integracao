<?php

namespace App\Application\UseCase\Empresa\Produtos;

use App\Domain\Entity\Empresa\Produtos\Produto;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\AtualizacaoStockRepository;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use App\Infra\Repository\Empresa\ProdutoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CadastrarProduto
{
    use TraitUploadFileProduto;

    private ProdutoRepository $produtoRepository;
    private ExistenciaStockRepository $existenciaStockRepository;
    private AtualizacaoStockRepository $atualizacaoStockRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->produtoRepository = $repositoryFactory->createProdutoRepository();
        $this->existenciaStockRepository = $repositoryFactory->createExistenciaStockRepository();
        $this->atualizacaoStockRepository = $repositoryFactory->createAtualizacaoStockRepository();
    }

    public function execute(Request $request)
    {
        $categoriaId = $request->subCategoria2 ? $request->subCategoria2 : ($request->subCategoria1 != "" || $request->subCategoria1 ? $request->subCategoria1 : $request->categoria_id);
        $cartaGarantia = $request->tempoGarantiaProduto ? true : false;
        foreach ($request->centrosCustos as $centroCustoId) {
            $urlImagemProduto = $this->uploadFile($request->imagem_produto);
            $produto = new Produto(
                $request->designacao,
                $request->preco_venda,
                $request->pvp,
                $request->preco_compra,
                $categoriaId,
                $request->categoria_id,
                $request->tipoServicoId,
                $request->subCategoria1,
                $request->subCategoria2,
                $request->unidade_medida_id,
                $request->fabricante_id,
                $request->armazem_id,
                $request->status_id,
                $request->codigo_taxa,
                $request->motivo_isencao_id,
                $request->quantidade_minima,
                $request->quantidade_critica,
                $request->quantidade,
                $urlImagemProduto,
                $request->codigo_barra,
                $request->referencia,
                $request->dataExpiracao,
                $request->descricao,
                $request->stocavel,
                $request->venda_online,
                $cartaGarantia,
                $request->tempoGarantiaProduto,
                $request->tipoGarantia,
                $centroCustoId
            );
            $outputProduto = $this->produtoRepository->salvar($produto);
            if (!$outputProduto) throw new \Exception("Erro ao cadastrar produto");
            foreach ($request->imagens as $imagem) {
                $urlImagemProdutoAdicional = $this->uploadFile($imagem);
                $this->produtoRepository->salvarImagensAdicional($urlImagemProdutoAdicional, $outputProduto->id);
            }
            $this->existenciaStockRepository->adicionarProdutoNoEstoque($produto, $outputProduto->id);
            $this->atualizacaoStockRepository->salvar($produto, $outputProduto->id);
        }
    }

}
