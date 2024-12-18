<?php

namespace App\Http\Controllers\empresa\Clientes;

use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\TipoDocumento;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RelatorioExtratoClienteController extends Component
{

    use LivewireAlert;
    
    public $clientes;
    public $extrato = [
        'clienteId' => null,
        'tipoDocumentoId' => null,
        'dataInicio' => null,
        'dataFinal' => null,
    ];
    public $todoPeriodo = false;
    public $tipoDocumentos;

    protected $listeners = ['selectedItem'];

    public function selectedItem($item)
    {
        $this->extrato[$item['atributo']] = $item['valor'];
    }

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function mount()
    {
        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $this->clientes = $getClientes->execute();
        $this->tipoDocumentos = TipoDocumento::whereIn('id', [2, 3, 6, 1])
            ->get();
    }

    public function render()
    {
        return view('empresa.clientes.extratoIndex');
    }

    public function imprimirExtratoCliente()
    {
        $rules = [
            'extrato.clienteId' => ["required"],
            'extrato.dataInicio' => [function ($attr, $dataInicio, $fail) {
                if (!$this->todoPeriodo && !$dataInicio) {
                    $fail("campo obrigatório");
                }
            }],
            'extrato.dataFinal' => [function ($attr, $dataFinal, $fail) {
                if (!$this->todoPeriodo && !$dataFinal) {
                    $fail("campo obrigatório");
                }
            }]
        ];
        $messages = [
            'extrato.clienteId.required' => 'campo obrigatório',
            'extrato.dataInicio.required' => 'campo obrigatório',
            'extrato.dataFinal.required' => 'campo obrigatório'
        ];

        $this->validate($rules, $messages);

        $factura = DB::table('facturas')->where('clienteId', $this->extrato['clienteId'])
            ->first();

        $recibo = DB::table('recibos')->where('clienteId', $this->extrato['clienteId'])
            ->first();


        if (!$factura && !$recibo) {
            $this->confirm('Não existe nenhum documento para este cliente', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $formatoData = $this->extrato['dataInicio'] . " até " . $this->extrato['dataInicio'];
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;

        $filename = "extratoCliente";
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'logotipo' => $logotipo,
                    'clienteId' => $this->extrato['clienteId'],
                    'empresaId' => auth()->user()->empresa_id,
                    'dataInicio' => $this->extrato['dataInicio'],
                    'dataFinal' => $this->extrato['dataFinal'],
                    'formatoData' => $formatoData
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
}
