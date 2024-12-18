<?php

namespace App\Http\Controllers\empresa\Operacao;
use App\Http\Controllers\empresa\ReportShowController;
use Illuminate\Support\Facades\DB;

trait TraitPrintNotaCredito
{

    public function printNotaCredito($notaCreditoId){

        $factura = DB::table('notas_creditos')
            ->where('id', $notaCreditoId)->first();
        if($factura->tipoFatura == 1){ //Serviço de carga
            $filename = "notaCreditoCarga";

        }else if($factura->tipoFatura == 2){//Serviço aeroportuario
            $filename = "notaCreditoAeroportuario";
        }else if($factura->tipoFatura == 3){//Serviço aeroportuario
            $filename = "notaCreditoOutroServico";
        }
        else if($factura->tipoFatura == 4){//Serviço aeroportuario
            $filename = "notaCreditoServicoComercial";
        }
        if ($factura->tipo_documento == 3) { //proforma
            $logotipo = public_path() . '/upload/_logo_ATO_vertical_com_TAG_color.png';
        } else {
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
                    "notaCreditoId" => $notaCreditoId,
                    "dirSubreportBanco" => $DIR,
                    "dirSubreportTaxa" => $DIR,
                    "tipo_regime" => auth()->user()->empresa->tipo_regime_id,
                    "nVia" => 1,
                    "DIR" => $DIR
                ]
            ], "pdf", $DIR_SUBREPORT
        );


        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        // $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }

}
