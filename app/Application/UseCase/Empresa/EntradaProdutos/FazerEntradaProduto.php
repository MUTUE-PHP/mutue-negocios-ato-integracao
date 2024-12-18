<?php

namespace App\Application\UseCase\Empresa\EntradaProdutos;
use App\Domain\Entity\Empresa\EntradaProduto\EntradaProduto;
use App\Domain\Entity\Empresa\EntradaProduto\ItemEntradaProduto;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\EntradaProdutoRepository;

class FazerEntradaProduto
{
    private EntradaProdutoRepository $entradaProdutoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->entradaProdutoRepository = $repositoryFactory->createEntradaProdutoRepository();
    }

    public function execute($request)
    {
        $request = (object)$request;

        $dataEntrada = new EntradaProduto(
            $request->numeracaoFatura,
            $request->fornecedorId,
            $request->armazemId,
            $request->formaPagamentoId,
            $request->dataEntrada,
            $request->dataFaturaFornecedor,
            $request->descontoValor,
            $request->descontoPercentagem,
            $request->totalRetencao,
            $request->statusPagamento,
            $request->descricao ?? null,
        );
        foreach ($request->items as $item){
            $item = (object) $item;
            $item = new ItemEntradaProduto(
                $item->quantidade,
                $item->produtoId,
                $item->precoVenda,
                $item->precoCompra,
                $item->descontoPercentagem,
                $item->descontoValor,
                $item->taxaIva
            );
            $dataEntrada->addItem($item);
        }
        $outputEntradaProduto = $this->entradaProdutoRepository->fazerEntradaProduto($dataEntrada);

        //faltando atualizar produto no estoque
        return response()->json($outputEntradaProduto, 200);
    }

}
