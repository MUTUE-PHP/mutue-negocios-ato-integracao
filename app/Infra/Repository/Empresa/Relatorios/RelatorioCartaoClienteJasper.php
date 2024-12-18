<?php

namespace App\Infra\Repository\Empresa\Relatorios;
use App\Http\Controllers\empresa\ReportShowApiController;
use App\Http\Controllers\empresa\ReportShowController;

Trait RelatorioCartaoClienteJasper
{
    public function imprimirCartaoCliente($cartaoClienteId){

        $cartaoClienteImg = public_path() . "/upload/cartaoClientes/cartaoCliente.png";

        $filename = "cartaoCliente3";
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'cartaoClienteId' => $cartaoClienteId,
                    'cartaoClienteImg' => $cartaoClienteImg,
                    'empresa_id' => auth()->user()->empresa_id
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
    public function imprimirHistoricoCartaoCliente($clienteId){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "historioCartaoCliente";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'clienteId' => $clienteId,
                    'logotipo' => $logotipo,
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }
    public function imprimirHistoricoCartaoClienteApi($clienteId){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "historioCartaoCliente";

        $reportController = new ReportShowApiController();
        return $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'clienteId' => $clienteId,
                    'logotipo' => $logotipo,
                ]
            ]
        );
    }

}
