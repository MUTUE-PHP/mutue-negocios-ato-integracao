<?php

namespace App\Http\Controllers\Api\HistoricoCartaoCliente;
use App\Application\UseCase\Empresa\HistoricoCartaoCliente\ListarExtratoCartaoCliente;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\Relatorios\RelatorioCartaoClienteJasper;
use Illuminate\Support\Facades\DB;

class HistoricoCartaoClienteIndexController extends Controller
{
    use RelatorioCartaoClienteJasper;


    public function listarExtratoCartaoCliente($numeroCartao)
    {
        $getExtratoCartaoCliente = new ListarExtratoCartaoCliente(new DatabaseRepositoryFactory());
        $extratoCartaoCliente = $getExtratoCartaoCliente->execute($numeroCartao);
        return response()->json([
            'data' => $extratoCartaoCliente?? null,
            'message' => 'listar extrato do cartão clientes'
        ], 200);
    }
    public function imprimirExtratoCartaoCliente($clienteId){

        $extratoCartaoCliente = DB::table('historico_extrato_cartao_cliente')->where('clienteId', $clienteId)
            ->first();
        if(!$extratoCartaoCliente){
            return response()->json([
                'data' => null,
                'message' => 'Não foi aplicado nenhum desconto/bonus no cartão'
            ]);
        }
        return $this->imprimirHistoricoCartaoClienteApi($clienteId);

    }

}
