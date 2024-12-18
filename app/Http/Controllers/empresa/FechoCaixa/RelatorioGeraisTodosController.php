<?php

namespace App\Http\Controllers\empresa\FechoCaixa;

use App\Http\Controllers\empresa\ReportShowController;
use App\Models\empresa\CentroCusto;
use App\Models\empresa\Cliente;
use App\Models\empresa\FormaPagamentoGeral;
use App\Models\empresa\FormaPagamentos;
use App\Models\empresa\Moeda;
use App\Models\empresa\TipoDocumento;
use App\Models\empresa\Tipodocumentosequencia;
use App\Models\empresa\TipoMercadoria;
use App\Models\empresa\TipoServico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class RelatorioGeraisTodosController extends Component
{
    use LivewireAlert;

    public $relatorio = [
        'dataInicio' => null,
        'dataFim' => null,
        'tipoDocumentoId' => null,
        'tipoServicoId' => null,
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

        $this->relatorio[$item['atributo']] = $item['valor'];
    }

    public function render()
    {
        $data['tiposDocumentos'] = TipoDocumento::whereIn('id', [1, 2, 3])->get();
        $data['tiposServicos'] = TipoServico::get();
        return view('empresa.relatorios.relatoriosGeral', $data);
    }

    public function imprimirRelatorioGeral()
    {
        $dataInicio = date_format(date_create($this->relatorio['dataInicio']), "d/m/Y") . " 00:00:00";
        $dataFinal = date_format(date_create($this->relatorio['dataFim']), "d/m/Y") . " 23:59:59";
        $formatoData = "TODO PERIODO";
        $setarData = false;

        if ($this->relatorio['dataInicio'] && $this->relatorio['dataFim']) {
            $formatoData = $dataInicio . " à " . $dataFinal;
            $setarData = true;
        }

        $rules = [
            'relatorio.dataInicio' => [function ($attribute, $dataInicio, $fail) {
                if ($dataInicio > $this->relatorio['dataFim']) {
                    $fail('data inicial é maior que a final');
                }
            }]
        ];
        $messages = [
            'relatorio.dataInicio' => 'campo obrigatório',
            'relatorio.dataFim' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);
        $dataInicio1 = $this->relatorio['dataInicio'] . " 00:00:00";
        $dataFinal1 = $this->relatorio['dataFim'] . " 23:59:59";
        $tipoDocumentoId = $this->relatorio['tipoDocumentoId'];
        $tipoServicoId = $this->relatorio['tipoServicoId'];

        $vendas = DB::table('facturas')
            ->where(function ($query) use ($tipoDocumentoId) {
                if ($tipoDocumentoId) {
                    $query->where('tipoDocumento', $tipoDocumentoId);
                } else {
                    $query->where('id', '>=', 1);
                }
            })->where(function ($query) use ($tipoServicoId) {
                if ($tipoServicoId) {
                    $query->where('tipoFatura', $tipoServicoId);
                } else {
                    $query->where('id', '>=', 1);
                }
            })->where(function ($query) use ($dataInicio1, $dataFinal1) {
                if ($dataInicio1 && $dataFinal1) {
                    $query->whereDate('created_at', '>=', $dataInicio1)
                        ->whereDate('created_at', '<=', $dataFinal1);
                } else {
                    $query->where('id', '>=', 1);
                }
            })->get();

        if (count($vendas) <= 0) {
            $this->confirm('Não existe dados com estes filtros', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "relatoriosDeVendasGerais";
        $reportController = new ReportShowController();

        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'logotipo' => $logotipo,
                    'tipoDocumentoId' => $this->relatorio['tipoDocumentoId'],
                    'tipoServicoId' => $this->relatorio['tipoServicoId'],
                    'data_inicio' => $this->relatorio['dataInicio'] . " 00:00:00",
                    'data_fim' => $this->relatorio['dataFim'] . " 23:59:59",
                    'formatoData' => $formatoData,
                    'setarData' => $setarData
                ]
            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

    public function imprimirExcelRelatorioGeral()
    {
        $dataInicio = date_format(date_create($this->relatorio['dataInicio']), "d/m/Y") . " 00:00:00";
        $dataFinal = date_format(date_create($this->relatorio['dataFim']), "d/m/Y") . " 23:59:59";

        $formatoData = "TODO PERIODO";
        $setarData = false;
        if ($this->relatorio['dataInicio'] && $this->relatorio['dataFim']) {
            $formatoData = $dataInicio . " à " . $dataFinal;
            $setarData = true;
        }

        $rules = [
            'relatorio.dataInicio' => [function ($attribute, $dataInicio, $fail) {
                if ($dataInicio > $this->relatorio['dataFim']) {
                    $fail('data inicial é maior que a final');
                }
            }]
        ];
        $messages = [
            'relatorio.dataInicio' => 'campo obrigatório',
            'relatorio.dataFim' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);
        $dataInicio1 = $this->relatorio['dataInicio'] . " 00:00:00";
        $dataFinal1 = $this->relatorio['dataFim'] . " 23:59:59";
        $tipoDocumentoId = $this->relatorio['tipoDocumentoId'];
        $tipoServicoId = $this->relatorio['tipoServicoId'];

        $vendas = DB::table('facturas')
            ->where(function ($query) use ($tipoDocumentoId) {
                if ($tipoDocumentoId) {
                    $query->where('tipoDocumento', $tipoDocumentoId);
                } else {
                    $query->where('id', '>=', 1);
                }
            })->where(function ($query) use ($tipoServicoId) {
                if ($tipoServicoId) {
                    $query->where('tipoFatura', $tipoServicoId);
                } else {
                    $query->where('id', '>=', 1);
                }
            })->where(function ($query) use ($dataInicio1, $dataFinal1) {
                if ($dataInicio1 && $dataFinal1) {
                    $query->whereDate('created_at', '>=', $dataInicio1)
                        ->whereDate('created_at', '<=', $dataFinal1);
                } else {
                    $query->where('id', '>=', 1);
                }
            })->get();

        if (count($vendas) <= 0) {
            $this->confirm('Não existe dados com estes filtros', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "relatoriosDeVendasGerais";
        $reportController = new ReportShowController('xls');

        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'logotipo' => $logotipo,
                    'tipoDocumentoId' => $this->relatorio['tipoDocumentoId'],
                    'tipoServicoId' => $this->relatorio['tipoServicoId'],
                    'data_inicio' => $this->relatorio['dataInicio'],
                    'data_fim' => $this->relatorio['dataFim'],
                    'formatoData' => $formatoData,
                    'setarData' => $setarData
                ]
            ]
        );


        $headers = array(
            'Content-Type: application/xls',
        );
        return \Illuminate\Support\Facades\Response::download($report['filename'], 'relatorioGeral' . Str::uuid() . '.xls', $headers);


    }

}
