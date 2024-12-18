<?php

namespace App\Infra\Repository\Empresa\Relatorios;
use App\Application\UseCase\Empresa\CartaGarantia\GetHabilitadoCartaGarantia;
use App\Application\UseCase\Empresa\Licencas\VerificarUserLogadoLicencaGratis;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;

Trait TraitRelatorioFaturacaoJasper
{
    public function imprimirDocumentoFaturacao($fatura, $filename = "Winmarket"){

        $output = $filename;
        $input = $filename . '.jrxml';
        $caminho = public_path() ."/upload/documentos/empresa/modelosFacturas/a4/";
        $path = "/upload/documentos/empresa/modelosFacturas/a4/";

        if($fatura->tipo_documento == 3){
            $logotipo = public_path() . '/upload/_logo_ATO_vertical_com_TAG_color.png';
        }else{
            $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;
        }

        $viewCartaGarantia = 'N';
        $parametroCartaGarantia = new GetHabilitadoCartaGarantia(new DatabaseRepositoryFactory());
        $parametroCartaGarantia = $parametroCartaGarantia->execute($fatura->id);
        if($parametroCartaGarantia){
            $viewCartaGarantia = 'Y';
        }
        $viewMarcaAguaTeste = 2; // não tem licença gratis
        $ultimaLicencaGratis = new VerificarUserLogadoLicencaGratis(new \App\Infra\Factory\Admin\DatabaseRepositoryFactory());
        $ativacaoLicenca = $ultimaLicencaGratis->execute();

        if($ativacaoLicenca){
            $viewMarcaAguaTeste = 1; // tem licença gratis
        }

        $reportController = new ReportShowController('pdf', $path);

        $report = $reportController->show(
            [
                'report_file' => $output,
                'report_jrxml' => $input,
                'report_parameters' => [
                    "viaImpressao" => 1,
                    "logotipo" => $logotipo,
                    "empresa_id" => auth()->user()->empresa_id,
                    "facturaId" => $fatura->id,
                    "dirSubreportBanco" => $caminho,
                    "dirSubreportTaxa" => $caminho,
                    "tipo_regime" => auth()->user()->empresa->tipo_regime_id,
                    "nVia" => 1,
                    "DIR" => $caminho
                ]
            ],
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        // $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
    public function imprimirDocumentoFormatoTicket($faturaId){
        $this->dispatchBrowserEvent('printFaturaTicket', ['data' => "/imprimirFacturaPdv1?facturaId=$faturaId"]);
    }
    public function imprimirRelatorioDiariaOperadorLogado(){

        $currentDate = date('Y-m-d');
        $isDocumentCurrentDate = DB::table('facturas')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('centroCustoId', session()->get('centroCustoId'))
            ->where('tipo_documento', 1) //factura recibo
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', $currentDate)
            ->get();
        if(count($isDocumentCurrentDate) <=0) throw new \Error("O utilizador logado não emitiu nenhuma fatura");

        $filename = "relatorioDiario";
        $output = $filename;
        $input = $filename . '.jrxml';
        $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $output,
                'report_jrxml' => $input,
                'report_parameters' => [
                    "diretorio" =>  $logotipo,
                    "empresa_id" =>  auth()->user()->empresa_id,
                    "centroCustoId" => session()->get('centroCustoId'),
                    "operador" =>  auth()->user()->id,
                    "dataAtual" => $currentDate
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

}
