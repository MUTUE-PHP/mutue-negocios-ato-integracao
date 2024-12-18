<?php

namespace App\Infra\Repository\VendasOnline;

use App\Domain\Entity\VendasOnline\PagamentoVendasOnline;
use App\Models\Portal\CarrinhoProduto;

class CarrinhoVendasOnlineRepository
{
    public function getCarrinhos($userId)
    {
        return CarrinhoProduto::with(['produto', 'produto.tipoTaxa'])
            ->where('users_id', $userId)
            ->get();
    }
    public function limparCarrinhoApartirUser($userId){
        return CarrinhoProduto::with(['produto'])->where('users_id', $userId)->delete();
    }
}
