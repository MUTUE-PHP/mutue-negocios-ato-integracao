<?php

namespace App\Infra\Repository\Empresa;
use App\Domain\Entity\Empresa\Produtos\Produto;
use App\Models\empresa\ExistenciaStock as ExistenciaStockDatabase;
use Illuminate\Support\Facades\DB;

class ExistenciaStockRepository
{
    public function isStock($item, $armazemId)
    {
        $item = (object) $item;
        return ExistenciaStockDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('produto_id', $item->produtoId)
            ->where('armazem_id', $armazemId)
            ->where('quantidade','>=', $item->quantidade)
            ->first();
    }
    public function atualizarExistenciaStock($produtoId, $armazemId, $quantidade){
        return ExistenciaStockDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('produto_id', $produtoId)
            ->where('armazem_id', $armazemId)->update([
                'quantidade' => $quantidade
            ]);
    }
    public function eliminarProduto($produtoId){
        $armazem = DB::connection('mysql2')->table('armazens')
            ->where('empresa_id', auth()->user()->empresa_id)->first();
        ExistenciaStockDatabase::where('produto_id', $produtoId)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('armazem_id', $armazem->id)
            ->delete();
    }

    public function adicionarProdutoNoEstoque(Produto $produto, $produtoId)
    {
        return ExistenciaStockDatabase::create([
            'produto_id' => $produtoId,
            'armazem_id' => $produto->getArmazemId(),
            'quantidade' => $produto->getQuantidade(),
            'canal_id' => 2,
            'user_id' => auth()->user()->id ?? 35,
            'status_id' => 1,
            'empresa_id' => auth()->user()->empresa_id ?? 53
        ]);
    }
}
