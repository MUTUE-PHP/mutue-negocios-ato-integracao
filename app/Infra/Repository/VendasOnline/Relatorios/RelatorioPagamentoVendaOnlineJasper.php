<?php

namespace App\Infra\Repository\VendasOnline\Relatorios;
use App\Application\UseCase\Empresa\CartaGarantia\GetHabilitadoCartaGarantia;
use App\Application\UseCase\Empresa\Licencas\VerificarUserLogadoLicencaGratis;
use App\Http\Controllers\empresa\ReportShowApiController;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;

trait RelatorioPagamentoVendaOnlineJasper
{
    public function imprimirPagamento($pagamentoId)
    {
        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $logotipo = public_path() . '/upload//' . $empresa->logotipo;

        $filename = "comprovativoCompraVendaOnline";
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'pagamentoId' => $pagamentoId,
                    'logoAdmin' => $logotipo
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

    public function imprimirFactura($facturaId)
    {
        $viewMarcaAguaTeste = 2; // não tem licença gratis
        $viewCartaGarantia = 'N';
        $parametroCartaGarantia = new GetHabilitadoCartaGarantia(new DatabaseRepositoryFactory());
        $parametroCartaGarantia = $parametroCartaGarantia->execute($facturaId);
        if ($parametroCartaGarantia) {
            $viewCartaGarantia = 'Y';
        }

        $factura = $this->facturaRepository->listarFactura($facturaId);
        $filename = "documentoTeste";
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $DIR_SUBREPORT = "/upload/documentos/empresa/modelosFacturas/a4/";
        $DIR = public_path() . "/upload/documentos/empresa/modelosFacturas/a4/";
        $reportController = new ReportShowController('pdf', $DIR_SUBREPORT);

        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    "empresa_id" => auth()->user()->empresa_id,
                    "logotipo" => $logotipo,
                    "facturaId" => $facturaId,
                    "viaImpressao" => 1,
                    "dirSubreportBanco" => $DIR,
                    "dirSubreportTaxa" => $DIR,
                    "viewNotaEntrega" => $factura['notaEntrega'],
                    "viewCartaGarantia" => $viewCartaGarantia,
                    "viewMarcaAguaTeste" => $viewMarcaAguaTeste,
                    "DIR" => $DIR,
                    "tipo_regime" => auth()->user()->empresa->tipo_regime_id,
                    "nVia" => 1
                ]
            ], "pdf", $DIR_SUBREPORT
        );


        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        // $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
}
