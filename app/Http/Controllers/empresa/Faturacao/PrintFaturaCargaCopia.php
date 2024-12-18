<?php

namespace App\Http\Controllers\empresa\Faturacao;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;

trait PrintFaturaCargaCopia
{
    public function printFaturaCarga($facturaId){

        $factura = $this->facturaRepository->listarFactura($facturaId);

        if ($factura['anulado'] == 2) {
            if($factura['tipo_documento'] == 3){ //proforma
                $logotipo = public_path() . '/upload/_logo_ATO_vertical_com_TAG_color.png';
            }else{
                $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
            }
            $DIR_SUBREPORT = "/upload/documentos/empresa/modelosFacturas/a4/";
            $DIR = public_path() . "/upload/documentos/empresa/modelosFacturas/a4/";
            $reportController = new ReportShowController('pdf', $DIR_SUBREPORT);
            $report = $reportController->show(
                [
                    'report_file' => 'WinmarketAnulado',
                    'report_jrxml' => 'WinmarketAnulado.jrxml',
                    'report_parameters' => [
                        "empresa_id" => auth()->user()->empresa_id,
                        "logotipo" => $logotipo,
                        "facturaId" => $facturaId,
                        "viaImpressao" => 2,
                        "dirSubreportBanco" => $DIR,
                        "dirSubreportTaxa" => $DIR,
                        "CaminhomarcaAgua" => $DIR,
                        "tipo_regime" => auth()->user()->empresa->tipo_regime_id,
                        "DIR" => $DIR,
                    ]

                ]
            );
        } else {
            $getParametro = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $parametro = $getParametro->execute('tipoImpreensao');

            $filename = $factura->tipoFatura == 1 ?"Winmarket":"Winmarket-Aeronave";
            if($parametro->valor == 'A5'){
                $filename = "Winmarket_A5";
            }
            if($factura['tipo_documento'] == 3){ //proforma
                $logotipo = public_path() . '/upload/_logo_ATO_vertical_com_TAG_color.png';
            }else{
                $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
            }
            $DIR_SUBREPORT = "/upload/documentos/empresa/modelosFacturas/a4/";
            $DIR = public_path() . "/upload/documentos/empresa/modelosFacturas/a4/";
            $reportController = new ReportShowController('pdf', $DIR_SUBREPORT);

            $report = $reportController->show(
                [
                    'report_file' => $filename,
                    'report_jrxml' => $filename . '.jrxml',
                    'report_parameters' => [
                        "viaImpressao" => 1,
                        "logotipo" => $logotipo,
                        "empresa_id" => auth()->user()->empresa_id,
                        "facturaId" => $facturaId,
                        "dirSubreportBanco" => $DIR,
                        "dirSubreportTaxa" => $DIR,
                        "tipo_regime" => auth()->user()->empresa->tipo_regime_id,
                        "nVia" => 1,
                        "DIR" => $DIR
                    ]
                ],"pdf",$DIR_SUBREPORT
            );
        }

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        // $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

}
