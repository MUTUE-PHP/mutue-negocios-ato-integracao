<?php

namespace App\Http\Controllers\Api\CartaoClientes;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientes;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;

class CartaoClienteIndexController extends Controller
{

    public function listarCartaoClientes($search = null)
    {
        $getCartaoCliente = new GetCartaoClientes(new DatabaseRepositoryFactory());
        $cartaoClientes = $getCartaoCliente->execute($search);
        return response()->json([
            'data' => $cartaoClientes?? null,
            'message' => 'listar cartão clientes'
        ], 200);
    }

}
