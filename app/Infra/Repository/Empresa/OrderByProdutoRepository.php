<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\OrderByProduto as OrderByProdutoDatabase;

class OrderByProdutoRepository
{
    public function getOrderByProduto(){
        return OrderByProdutoDatabase::get();
    }
}
