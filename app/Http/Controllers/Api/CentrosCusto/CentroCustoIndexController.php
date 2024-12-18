<?php

namespace App\Http\Controllers\Api\CentrosCusto;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientes;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCusto;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;

class CentroCustoIndexController extends Controller
{

    public function listarCentrosCusto($search = null)
    {
        $getCentrosCusto = new GetCentrosCusto(new DatabaseRepositoryFactory());
        $getCentrosCusto = $getCentrosCusto->execute($search);
        return response()->json([
            'data' => $getCentrosCusto?? null,
            'message' => 'listar centros de custo'
        ], 200);
    }
}
