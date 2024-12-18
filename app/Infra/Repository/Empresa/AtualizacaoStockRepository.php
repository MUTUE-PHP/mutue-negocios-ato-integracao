<?php

namespace App\Infra\Repository\Empresa;
use App\Domain\Entity\Empresa\Produtos\Produto;
use App\Models\empresa\AtualizacaoStocks as AtualizacaoStocksDatabase;

class AtualizacaoStockRepository
{
    public function salvar(Produto $produto, $produtoId){

        return AtualizacaoStocksDatabase::create([
            'empresa_id' => auth()->user()->empresa_id ?? 53,
            'produto_id' => $produtoId,
            'quantidade_antes' => $produto->getQuantidade(),
            'quantidade_nova' => $produto->getQuantidade(),
            'user_id' => auth()->user()->id ?? 35,
            'centroCustoId' => session()->get('centroCustoId'),
            'canal_id' => 2,
            'status_id' => 1,
            'armazem_id' => $produto->getArmazemId(),
        ]);
    }
    public function eliminarProduto($produtoId){
        AtualizacaoStocksDatabase::where('produto_id', $produtoId)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('centroCustoId', session()->get('centroCustoId'))
            ->delete();
    }
}
