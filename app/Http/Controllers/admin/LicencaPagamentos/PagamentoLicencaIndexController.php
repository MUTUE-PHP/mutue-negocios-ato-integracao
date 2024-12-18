<?php

namespace App\Http\Controllers\admin\LicencaPagamentos;
use App\Application\UseCase\Admin\Licenca\GetPagamento;
use App\Application\UseCase\Admin\Licenca\GetPagamentos;
use App\Http\Controllers\admin\ReportShowAdminController;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PagamentoLicencaIndexController extends Component
{
    use LivewireAlert;

    public $search = null;
    public $filter = [
        'dataInicial' => null,
        'dataFinal' => null,
        'search' => null
    ];

    public function render()
    {
        $getPagamentos = new GetPagamentos(new DatabaseRepositoryFactory());
        $data['pagamentos'] = $getPagamentos->execute($this->filter);
        return view('admin.pagamentoLicencas.index', $data)->layout('layouts.appAdmin');
    }

    public function visualizarRecibo($pagamentoId)
    {

        $getPagamento = new GetPagamento(new DatabaseRepositoryFactory());
        $pagamento = $getPagamento->execute($pagamentoId);


        $filename = 'reciboPagamentoPedente';
        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $empresaCliente = DB::connection('mysql')->table('empresas')->where('referencia', $pagamento['empresa']['referencia'])->first();
        $logotipo = public_path() . '/upload//' . $empresa->logotipo;

        $reportController = new ReportShowAdminController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'viaImpressao' => 1,
                    'pagamentoId' => $pagamento['id'],
                    'logotipo' => $logotipo,
                    'empresa_id' => $empresaCliente->id,
                    'EmpresaNome' => $empresa->nome,
                    'EmpresaEndereco' => $empresa->endereco,
                    'EmpresaNif' => $empresa->nif,
                    'EmpresaTelefone' => $empresa->pessoal_Contacto,
                    'EmpresaEmail' => $empresa->email,
                    'EmpresaWebsite' => $empresa->website,
                    'operador' => auth()->user()->name
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }
    public function visualizarComprovativoPagamento($comprovativoBancario){
        return response()->file(public_path('upload/' . $comprovativoBancario));
    }
    public function visualizarFatura($pagamentoId){

        $getPagamento = new GetPagamento(new DatabaseRepositoryFactory());
        $pagamento = $getPagamento->execute($pagamentoId);

        $filename = 'facturaA4Admin';
        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $empresaCliente = DB::connection('mysql')->table('empresas')->where('referencia', $pagamento['empresa']['referencia'])->first();
        $logotipo = public_path() . '/upload//' . $empresa->logotipo;
        $DIR = public_path() . "/upload/documentos/admin/relatorios/";

        $reportController = new ReportShowAdminController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'viaImpressao' => 1,
                    'facturaId' => $pagamento['fatura']['id'],
                    'logotipo' => $logotipo,
                    'empresa_id' => $empresaCliente->id,
                    'EmpresaNome' => $empresa->nome,
                    'EmpresaEndereco' => $empresa->endereco,
                    'EmpresaNif' => $empresa->nif,
                    'EmpresaTelefone' => $empresa->pessoal_Contacto,
                    'EmpresaEmail' => $empresa->email,
                    'EmpresaWebsite' => $empresa->website,
                    'operador' => auth()->user()->name,
                    'DIR' => $DIR
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
    public function imprimirPagamentos()
    {

        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();

        $data = false;
        if (!$this->filter['dataInicial'] && !$this->filter['dataFinal']) {
            $formatoData = "TODO PERIODO";
        }else if ($this->filter['dataInicial'] && !$this->filter['dataFinal']) {
            $this->confirm('Informe a data final', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        } else if (!$this->filter['dataInicial'] && $this->filter['dataFinal']) {
            $this->confirm('Informe a data inicial', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        } else {
            $dataInicial = \DateTime::createFromFormat('Y-m-d',$this->filter['dataInicial']);
            $dataFinal = \DateTime::createFromFormat('Y-m-d', $this->filter['dataFinal']);
            $dataInicial = $dataInicial->format('d/m/Y');
            $dataFinal = $dataFinal->format('d/m/Y');
            $formatoData = $dataInicial . ' à ' . $dataFinal;
            $data = true;
        }

        $logotipo = public_path() . '/upload//' . $empresa->logotipo;
        $caminho = public_path() . '/upload/documentos/admin/relatorios/';
        $filename = "pagamentos";
        $reportController = new ReportShowAdminController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'logotipo' => $logotipo,
                    'formatoData' => $formatoData,
                    'diretorio' => $caminho,
                    'dataInicial' => $data ? $this->filter['dataInicial'] . ' 01:00' : '2020-01:01 01:00',
                    'dataFinal' => $data ? $this->filter['dataFinal'] . ' 23:59' : Carbon::now()->format('Y-m-d'). ' 23:59',
                ]
            ]
       
        );
       
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }
}
