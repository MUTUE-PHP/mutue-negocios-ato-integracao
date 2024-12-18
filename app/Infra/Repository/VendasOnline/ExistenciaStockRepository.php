<?php

namespace App\Infra\Repository\VendasOnline;

use App\Models\empresa\ExistenciaStock;

class ExistenciaStockRepository
{
    public function atualizarStock($produtoId, $quantidade, $armazemId, $operacao)
    {
        if ($operacao == "+") {
            return ExistenciaStock::where('produto_id', $produtoId)
                ->where('armazem_id', $armazemId)->increment('quantidade', $quantidade);
        }
        return ExistenciaStock::where('produto_id', $produtoId)
            ->where('armazem_id', $armazemId)->decrement('quantidade', $quantidade);
    }
}
