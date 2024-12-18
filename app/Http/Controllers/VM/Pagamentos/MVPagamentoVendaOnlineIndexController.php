<?php

namespace App\Http\Controllers\VM\Pagamentos;

use App\Application\UseCase\VendasOnline\PagamentoCompras\ImprimirPagamentoCompraVendaOnline;
use App\Application\UseCase\VendasOnline\PagamentoCompras\ListarPagamentosRejeitadoUtilizadorEspecificoAutenticado;
use App\Application\UseCase\VendasOnline\PagamentoCompras\ListarPagamentosUtilizadorEspecificoAutenticado;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;

class MVPagamentoVendaOnlineIndexController extends Controller
{
    public function listarPagamentoUser(Request $request){

        $getPagamentos = new ListarPagamentosUtilizadorEspecificoAutenticado(new DatabaseRepositoryFactory());
        $output = $getPagamentos->execute($request);
        return response()->json($output, 200);
    }
    public function listarPagamentoRejeitadoUser(Request $request){
        $getPagamentos = new ListarPagamentosRejeitadoUtilizadorEspecificoAutenticado(new DatabaseRepositoryFactory());
        $output = $getPagamentos->execute($request);
        return response()->json($output, 200);
    }
    public function imprimirPagamentoVendasOnline($pagamentoId){
        $proforma = new ImprimirPagamentoCompraVendaOnline(new DatabaseRepositoryFactory());
        return $proforma->execute($pagamentoId);
    }
}
