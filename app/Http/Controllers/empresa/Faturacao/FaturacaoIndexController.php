<?php

namespace App\Http\Controllers\empresa\Faturacao;
use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamento;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPorArmazem;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\Auth;

class FaturacaoIndexController extends Controller
{
    public function create(){
        $data = [];

        $getArmazens = new GetArmazens(new DatabaseRepositoryFactory());
        $data['armazens'] = $getArmazens->execute();

        $getFormasPagamentos = new GetFormasPagamento(new DatabaseRepositoryFactory());
        $data['formaspagamentos'] = $getFormasPagamentos->execute();
        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $data['clientes'] = $getClientes->execute();

        $data['guard'] = Auth::guard('empresa')->user();
        return view('empresa.facturacao.novo', $data);
    }
}
