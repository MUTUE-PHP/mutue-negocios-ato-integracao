<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Categoriacaracteristicas;
use App\Models\empresa\ValorCaracteristicasProdutos;

class CarateristicaProdutoRepository
{
    public function gerCarateristicasProduto(){
        return Categoriacaracteristicas::get();
    }
    public function getValorCarateristica($produtoId){
        return ValorCaracteristicasProdutos::with(['valorCarateristica'])->where('produto_id', $produtoId)->get();
    }

}
