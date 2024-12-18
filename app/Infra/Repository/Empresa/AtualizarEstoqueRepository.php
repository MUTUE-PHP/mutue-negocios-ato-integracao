<?php

namespace App\Infra\Repository\Empresa;

use App\Domain\Entity\Empresa\Estoque\AtualizarEstoque;
use App\Models\empresa\AtualizacaoStocks as AtualizacaoStocksDatabase;

class AtualizarEstoqueRepository
{

    public function atualizarEstoque(AtualizarEstoque $atualizarEstoque)
    {
        return AtualizacaoStocksDatabase::create([
            'empresa_id' => auth()->user()->empresa->id,
            'produto_id' => $atualizarEstoque->getProdutoId(),
            'quantidade_antes' => $atualizarEstoque->getQuantidadeAnterior(),
            'quantidade_nova' => $atualizarEstoque->getQuantidadeNova(),
            'user_id' => auth()->user()->id,
            'tipo_user_id' => 2,
            'canal_id' => 2,
            'status_id' => 1,
            'armazem_id' => $atualizarEstoque->getArmazemId(),
            'descricao' => $atualizarEstoque->getDescricao(),
            'centroCustoId' => $atualizarEstoque->getCentroCustoId(),
        ]);
    }

    public function getAtualizacoesEstoque($filtro)
    {
        return AtualizacaoStocksDatabase::with(['produtos', 'armazens', 'status', 'user'])
            ->where('empresa_id', auth()->user()->empresa_id)
            ->filter($filtro)
            ->paginate();
    }

}
