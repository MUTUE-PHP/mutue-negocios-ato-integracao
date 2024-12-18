<?php

namespace App\Application\UseCase\Empresa\EntradaProdutos;

use App\Domain\Entity\Empresa\EntradaProduto\EntradaProduto;
use App\Domain\Entity\Empresa\EntradaProduto\ItemEntradaProduto;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\EntradaProdutoRepository;

class SimularEntradaProduto
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
        $item = new ItemEntradaProduto(
            $request->item['quantidade'],
            $request->item['produtoId'],
            $request->item['nomeProduto'],
            $request->item['precoVenda'],
            $request->item['precoCompra'],
            $request->item['descontoPercentagem'],
            $request->item['descontoValor'],
            $request->item['taxaIva']
        );
        $dataEntrada->addItem($item);
        return $dataEntrada;
    }

}
