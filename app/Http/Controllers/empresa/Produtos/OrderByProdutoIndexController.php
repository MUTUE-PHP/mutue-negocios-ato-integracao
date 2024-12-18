<?php

namespace App\Http\Controllers\empresa\Produtos;

use App\Application\UseCase\Empresa\Produtos\GetOrderByProdutos;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;

class OrderByProdutoIndexController extends Controller
{
    public function listarOrderByProduto(){

        $getOrderByProduto = new GetOrderByProdutos(new DatabaseRepositoryFactory());
        return $getOrderByProduto->execute();
    }

}
