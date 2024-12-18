<?php

namespace App\Http\Controllers\empresa\Facturas;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaAeroportuario;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaServicoComerciais;
use App\Http\Controllers\empresa\Operacao\TraitPrintNotaCredito;
use App\Http\Controllers\empresa\ReportShowController;
use App\Models\empresa\Factura;
use App\Repositories\Empresa\FacturaRepository;
use App\Repositories\Empresa\ParametroRepository;
use App\Traits\Empresa\TraitEmpresaAutenticada;
use Livewire\Component;
use Livewire\WithPagination;

class FacturasServicoComercialIndexController extends Component
{
    use TraitEmpresaAutenticada;
    use WithPagination;
    use PrintFaturaServicoComerciais;
    use TraitPrintNotaCredito;


    protected $paginationTheme = 'bootstrap';
    public $search = null;
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
    public function imprimirFaturasAeroportuário($formato){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "faturaAeroportuarios";
        $reportController = new ReportShowController($formato);
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
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
            return \Illuminate\Support\Facades\Response::download($report['filename'], 'faturas-aeroportuario.xls', $headers);
        }
    }

    public function render()
    {
        $centrosCusto = auth()->user()->centrosCusto;
        if (!$centrosCusto) return redirect()->back();
        $data['centrosCusto'] = $centrosCusto;
        $data['facturas'] = Factura::with(['notaCredito'])
            ->where('tipoFatura', 4)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->filter($this->filter)
            ->search(trim($this->search))
            ->paginate();


        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.facturas.facturasServicosComerciaisIndex', $data);
    }
    public function imprimirFacturaAnulado($notaCreditoId){
        $this->printNotaCredito($notaCreditoId);
    }
    public function imprimirFactura($facturaId)
    {
        $this->printFaturaServicoComercias($facturaId);

    }

}
