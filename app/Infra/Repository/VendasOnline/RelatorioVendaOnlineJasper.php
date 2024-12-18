<?php

namespace App\Infra\Repository\VendasOnline;

use App\Http\Controllers\empresa\ReportShowApiController;
use App\Http\Controllers\empresa\ReportShowController;
use Illuminate\Support\Facades\DB;

class RelatorioVendaOnlineJasper
{
    public function imprimirPagamentoVendaOnline($pagamentoId)
    {

        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $logoAdmin = public_path() . '/upload//' . $empresa->logotipo;

        $filename = 'comprovativoCompraVendaOnline';
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'logoAdmin' => $logoAdmin,
                    'pagamentoId' => $pagamentoId
                ]
            ]
        );
        return $report;
    }

    public function imprimirPagamentoVendaOnline2($pagamentoId){

        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $logoAdmin = public_path() . '/upload//' . $empresa->logotipo;
        $filename = 'comprovativoCompraVendaOnline';
        $reportController = new ReportShowApiController();
        return $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'logoAdmin' => $logoAdmin,
                    'pagamentoId' => $pagamentoId
                ]
            ]
        );
    }
    public function imprimirProformaCarrinho(){

        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $cliente = DB::connection('mysql2')->table('clientes')->where('user_id', auth()->user()->id)->first();
        $logoAdmin = public_path() . '/upload//' . $empresa->logotipo;

        $filename = 'proformasVendasOnline';
        $reportController = new ReportShowApiController();
        return $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'logoAdmin' => $logoAdmin,
                    'clienteNome' => $cliente->nome,
                    'clienteEndereco' => $cliente->endereco,
                    'clienteTelefone' => $cliente->telefone_cliente,
                    'userId' => auth()->user()->id
                ]
            ]
        );
    }
}
