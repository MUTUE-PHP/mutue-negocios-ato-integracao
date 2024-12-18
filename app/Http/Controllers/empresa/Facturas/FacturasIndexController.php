<?php

namespace App\Http\Controllers\empresa\Facturas;


use App\Application\UseCase\Empresa\CartaGarantia\GetHabilitadoCartaGarantia;
use App\Application\UseCase\Empresa\Licencas\VerificarUserLogadoLicencaGratis;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaCarga;
use App\Http\Controllers\empresa\Operacao\TraitPrintNotaCredito;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura;
use App\Repositories\Empresa\FacturaRepository;
use App\Repositories\Empresa\ParametroRepository;
use App\Traits\Empresa\TraitEmpresaAutenticada;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;
use PHPJasper\PHPJasper;


class FacturasIndexController extends Component
{
    use TraitEmpresaAutenticada;
    use WithPagination;
    use PrintFaturaCarga;
    use TraitPrintNotaCredito;

    protected $paginationTheme = 'bootstrap';
    public $search;
    private $facturaRepository;
    private $parametroRepository;
    public $filter = [
        'tipoDocumentoId' => null,
        'centroCustoId' => null,
        'orderBy' => 'DESC',
        'dataInicial' => null,
        'dataFinal' => null,
        'search' => null
    ];
    protected $listeners = [
        'selectedItem'
    ];
    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->resetPage();
        $this->filter[$item['atributo']] = $item['valor'];
    }
    public function boot(FacturaRepository $facturaRepository, ParametroRepository $parametroRepository)
    {
        $this->facturaRepository = $facturaRepository;
        $this->parametroRepository = $parametroRepository;
    }

    public function render()
    {
        $centrosCusto = auth()->user()->centrosCusto;
        if (!$centrosCusto) return redirect()->back();
        $data['centrosCusto'] = $centrosCusto;
        $data['facturas'] = Factura::where('tipoFatura', 1)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->filter($this->filter)
            ->search(trim($this->search))
            ->paginate();

//        $data['facturas'] = $this->facturaRepository->listarfacturas($this->filter);
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.facturas.facturasIndex', $data);
    }
    public function imprimirFaturasCarga($formato){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "faturaCargas";
        $reportController = new ReportShowController($formato);
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'tipoDocumentoId' => $this->filter['tipoDocumentoId'],
                    'centroCustoId' => $this->filter['centroCustoId'],
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
            return \Illuminate\Support\Facades\Response::download($report['filename'], 'faturas-cargas.xls', $headers);
        }
    }
    public function imprimirFacturaAnulado($notaCreditoId){
        $this->printNotaCredito($notaCreditoId);
    }

    public function imprimirFactura($facturaId)
    {
        $this->printFaturaCarga($facturaId);
    }
    public function getDatabaseConfig()
    {
        return [

            'driver' => 'mysql', //mysql, ....
            'username' => env('DB_USERNAME2'),
            'password' => env('DB_PASSWORD2'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE2'),
            'port' => '3306'
        ];
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
