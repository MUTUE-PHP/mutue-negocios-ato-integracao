<?php

namespace App\Http\Controllers\empresa\Recibos;

use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Recibos;
use App\Repositories\Empresa\ReciboRepository;
use App\Traits\Empresa\TraitEmpresaAutenticada;
use App\Traits\VerificaSeEmpresaTipoAdmin;
use App\Traits\VerificaSeUsuarioAlterouSenha;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;
use PHPJasper\PHPJasper;

class ReciboIndexController extends Component
{

    use VerificaSeEmpresaTipoAdmin;
    use VerificaSeUsuarioAlterouSenha;
    use TraitEmpresaAutenticada;
    use WithFileUploads;
    use WithPagination;


    public $recibo;
    public $search;
    public $comprovativoBancario;
    public $filter = [
        'tipoDocumentoId' => null,
        'centroCustoId' => null,
        'orderBy' => 'DESC',
        'dataInicial' => null,
        'dataFinal' => null,
        'search' => null
    ];

    private $reciboRepository;

    public function boot(ReciboRepository $reciboRepository)
    {
        $this->reciboRepository = $reciboRepository;
    }

    public function render()
    {
        $infoNotificacao = $this->alertarActivacaoLicenca();
        $data['alertaAtivacaoLicenca'] = $infoNotificacao;

        if ($infoNotificacao['diasRestantes'] === 0) {
            return redirect("empresa/home");
        }
        if ($this->isAdmin()) {
            return view('admin.dashboard');
        }


        // $data['recibos'] = Recibos::filter($this->filter)
        // ->search(trim($this->search))
        // ->paginate();


         $data['recibos'] = $this->reciboRepository->listarRecibos($this->search);
          
         $data['totalRecibos'] = Recibos::count();

        return view('empresa.recibos.index', $data);
    }
    public function printRecibo($reciboId)
    {
        $recibo = $this->reciboRepository->listarRecibo($reciboId);

        $logotipo = public_path() . '/upload/AtoNegativo1.png';
        $caminho = public_path() . '/upload/documentos/empresa/relatorios/';
        $marcaDaAgua = public_path() . "/marca_agua.png";

        $getParametro = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $parametro = $getParametro->execute('tipoImpreensao');

        $filename = "recibos";
        if($parametro->valor == 'A5'){
            $filename = "recibos_A5";
        }
        if ($recibo['anulado'] == 2) { //Tipo anulado
            $filename = 'recibosAnulados';
        }

        $reportController = new ReportShowController();

        $report = $reportController->show([
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'viaImpressao' => 2,
                    'empresa_id' => auth()->user()->empresa_id,
                    'recibo_id' => $recibo['id'],
                    'factura_id' => $recibo['facturaId'],
                    'logotipo' => $logotipo,
                    "marcaDaAgua"=>$marcaDaAgua
                ]
        ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }
    public function visualizarComprovativo($recibo){

        $comprovativo = env('APP_URL')."upload/" . $recibo['comprovativoBancario'];
        // dd($comprovativo);
        $this->comprovativoBancario = $comprovativo;
    }


    public function ImprimirRelatoriosRecibos($formato){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "RelatoriosRecibos";
        $reportController = new ReportShowController($formato);
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    // 'tipoDocumentoId' => $this->filter['tipoDocumentoId'],
                    // 'centroCustoId' => $this->filter['centroCustoId'],
                    'data_inicio' => $this->filter['dataInicial'],
                    'data_fim' => $this->filter['dataFinal'],
                    'diretorio' => $logotipo,
                ]

            ]
        );

        if($formato == 'pdf'){
            $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
            unlink($report['filename']);
            flush();
        }else{
            $headers = array(
                'Content-Type: application/xls',
            );
            return \Illuminate\Support\Facades\Response::download($report['filename'], 'Recibos-Geral.xls', $headers);
        }

       


    }

 public function show($param, $formato, $path)
    {
        // instancia um novo objeto JasperPHP
        $report = new PHPJasper();

        // coloca na variavel o caminho do novo relatório que será gerado

        // coloca na variavel o caminho do novo relatÃ³rio que serÃ¡ gerado
        $output = public_path() . $path . time() . $param['report_file'];

        $input = public_path() . $path . $param['report_jrxml'];

        if (count($param['report_parameters'])) {
            $options['params'] = $param['report_parameters'];
        }
        $options['locale'] = 'pt';
        $options['format'] = [$formato];




        // chama o mÃ©todo que irÃ¡ gerar o relatÃ³rio
        // passamos por parametro:
        // o arquivo do relatÃ³rio com seu caminho completo
        // o nome do arquivo que serÃ¡ gerado
        // o tipo de saÃ­da
        // parametros ( nesse caso nÃ£o tem nenhum)

        $options['db_connection'] = $this->getDatabaseConfig();

        $report->process(
            $input,
            $output,
            $options
        )->execute();

        $filename = $output . '.' . $formato;

        // $header = [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Description' => 'application/pdf',
        //     'Content-Disposition' => 'filename=' . time() . $param['report_file']

        // ];

        // caso o arquivo nÃ£o tenha sido gerado retorno um erro 404
        if (!file_exists($filename)) {
            abort(404);
        }


        $response = Response::make(file_get_contents($filename), 200, [
            'Content-Type' => $formato == 'xls' ? 'application/vnd.ms-exce' : 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);

        return [
            'response' => $response,
            'filename' => $filename,
            'file' => $path . time() . $param['report_file'] . '.' . $formato
        ];
    }

}
