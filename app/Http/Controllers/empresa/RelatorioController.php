<?php

namespace App\Http\Controllers\empresa;

use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamento;
use App\Application\UseCase\Empresa\Fornecedores\GetFornecedores;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\CentroCusto;
use App\Models\empresa\Factura;
use App\Models\empresa\FormaPagamentoGeral;
use App\Models\empresa\User;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RelatorioController extends Component
{
    use LivewireAlert;

    public $venda;
    public $centroCusto;
    public $dataVendaTodoOperador;
    public $dataVendaPorOperador;
    public $data_inicio;
    public $data_fim;
    public $operadores;
    public $formaPagamentos;
    public $fornecedorId = 0;
    public $formaPagamentoId = 0;
    public $fornecedores;
    public $operadorSelecionado;

    public $dataInicial;
    public $dataFinal;


    public function hydrate()
    {
        $this->emit('select2');
    }

    protected $listeners = [
        'selectedItem'
    ];

    public function selectedItem($item)
    {
        if($item['atributo'] == 'formaPagamentoId'){
            $this->formaPagamentoId = $item['valor'];
        }else if($item['atributo'] == 'fornecedorId'){
            $this->fornecedorId = $item['valor'];
        }else{
            $this->operadorSelecionado = $item['valor'];
        }
    }

    public function mount()
    {
        $this->centroCusto = CentroCusto::where('empresa_id', auth()->user()->empresa_id)->first();
        if (!$this->centroCusto) {
            return redirect()->back();
        }

        $this->operadores = User::where('empresa_id', auth()->user()->empresa_id)->get();
        if (!$this->operadores) {
            return redirect()->back();
        }

        $fornecedores  = new GetFornecedores(new DatabaseRepositoryFactory());
        $this->fornecedores = $fornecedores->execute();

        $this->formaPagamentos = FormaPagamentoGeral::where('id', '!=', 6)->get();


    }

    public function boot()
    {

        $this->venda['dataFim'] = NULL;
        $this->venda['dataInicio'] = NULL;
    }

    public function render()
    {

        return view('empresa.relatorios.index', [
            'centroCusto' => $this->centroCusto,
            'operadores' => $this->operadores
        ]);
    }

    public function printRelatorioVendaTodosOperadoresPdf($format)
    {
        $data_atual = $this->dataVendaTodoOperador . " 00:00";
        $dataFim = $this->dataVendaTodoOperador . " 23:59";




        $rules = [
            'dataVendaTodoOperador' => ["required", function ($attr, $value, $fail) use ($dataFim, $data_atual) {
                $countFactura = DB::table('facturas')->where('empresa_id', auth()->user()->empresa_id)
                    ->where('created_at', '>=', $data_atual)
                    ->where('created_at', '<=', $dataFim)
                    ->get();

                if (count($countFactura) <= 0) {
                    $fail("Não existe vendas neste intervalo");
                }
            }],
        ];

    

        $messages = [
            'dataVendaTodoOperador.required' => 'Informe a data'
        ];


        $this->validate($rules, $messages);

        $filename = 'vendaDiaria';

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;

        $DIR = public_path() . '/upload/documentos/empresa/relatorios/';


        // empresaId = 158
        // data inicio = 2022-02-21 08:00
        // data fim = 2022-02-23 21:00
        // logotipo = C:\laragon\www\mutue-negocios\public/upload//utilizadores/cliente/laAJKtYOSjaLBTSjPlhDFfwmincv6pGBxAtuCThh.png
        // formatoPeriodo = 22-02-2022 08:00 as 21:00
        // DIR = C:\laragon\www\mutue-negocios\public/upload/documentos/empresa/relatorios//


        $reportController = new ReportShowController();

        $report = $reportController->show(

            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'data_atual' => $data_atual,
                    // 'data_fim' => $dataFim,
                    'logotipo' => $logotipo,
                    // 'formatoPeriodo' => $formatoPeriodo,
                    // 'DIR' => $DIR,


                ]

            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();


    }

    public function printRelatorioVendaPorOperadoresPdf()
    {
        
        $dataInicioFormat = date_format(date_create($this->data_inicio), "d/m/Y H:i:s");
        $dataFinalFormat = date_format(date_create($this->data_fim), "d/m/Y H:i:s");
        $request = $this->data_inicio;
        $request = $this->data_fim;


        $rules = [
            'data_inicio' => ["required", function ($attribute, $value, $fail) use ($request) {
                if ($request['data_inicio'] > $request['data_fim']) {
                    $fail('data inicial é maior que a final');
                    return;
                }
            }],
            'data_fim' => 'required',
            'data_inicio' => 'required',
            'operadores' => 'required',
        ];
        $messages = [
            'data_inicio.required' => 'Informe a data Inicial',
            'data_fim.required' => 'Informe a data Final',
            'operadores.required' => 'Informe o operador',
        ];
        $this->validate($rules, $messages);


        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "relatorioVendaDiarioPorOperador";
        $reportController = new ReportShowController();

        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'centroCustoId' => session()->get('centroCustoId'),
                    'logotipo' => $logotipo,
                    'user_id' => $this->operadorSelecionado,
                    'data_inicio' => $this->data_inicio,
                    'data_fim' => $this->data_fim,
                    'dataInicioFormat' => $dataInicioFormat,
                    'dataFinalFormat' => $dataFinalFormat,

                ]
            ]
        );


        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();


    }
    public function printRelatorioEntradaStock($formato){

//        dd($this->fornecedorId);
        $dataInicioFormat = date_format(date_create($this->dataInicial), "d/m/Y H:i:s");
        $dataFinalFormat = date_format(date_create($this->dataFinal), "d/m/Y H:i:s");
        $request['dataInicial'] = $this->dataInicial;
        $request['dataFinal'] = $this->dataFinal;
        $rules = [
            'dataInicial' => ["required", function ($attribute, $value, $fail) use ($request) {
                if ($request['dataInicial'] > $request['dataFinal']) {
                    $fail('data inicial é maior que a final');
                    return;
                }
            }],
            'dataFinal' => 'required',
            'dataInicial' => 'required',
        ];
        $messages = [
            'dataInicial.required' => 'Informe a data Inicial',
            'dataFinal.required' => 'Informe a data Final',
        ];
        $this->validate($rules, $messages);


        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "relatoriosDeEntradaStock";
        $reportController = new ReportShowController($formato);

        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'centroCustoId' => session()->get('centroCustoId'),
                    'logotipo' => $logotipo,
                    'fornecedorId' => (int)$this->fornecedorId,
                    'formaPagamentoId' => (int)$this->formaPagamentoId,
                    'dataInicial' => $this->dataInicial,
                    'dataFinal' => $this->dataFinal,
                    'dataInicioFormat' => $dataInicioFormat,
                    'dataFinalFormat' => $dataFinalFormat,
                ]
            ]
        );

        if($formato == 'xls'){
            $headers = array(
                'Content-Type: application/xls',
            );
            return \Illuminate\Support\Facades\Response::download($report['filename'], 'relatoriosDeEntradaStock.xls', $headers);
        }else{
            $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
            unlink($report['filename']);
            flush();
        }


    }

    public function printRelatorioVendaPdf($format)
    {
        $this->printRelatorioVenda($format);
    }

    public function printRelatorioVendaPdfMulticaixa($format)
    {
        $this->printRelatorioVendaMulticaixa($format);
    }

    public function printRelatorioVendaPdfCash($format)
    {
        $this->printRelatorioVendaCash($format);
    }

    public function printRelatorioVendaXls($format)
    {

        $request = $this->venda;
        $rules = [
            'venda.dataInicio' => ["required", function ($attribute, $value, $fail) use ($request) {
                if ($request['dataInicio'] > $request['dataFim']) {
                    $fail('data inicial é maior que a final');
                    return;
                }
            }],
            'venda.dataFim' => 'required',
        ];
        $messages = [
            'venda.dataInicio.required' => 'Informe as duas datas',
            'venda.dataFim.required' => 'Informe as duas datas',
        ];

        $this->validate($rules, $messages);


        $dataInicio = str_replace("T", " ", $this->venda['dataInicio']);
        $dataFim = str_replace("T", " ", $this->venda['dataFim']);
        $dataInicioFormat = date_format(date_create($dataInicio), 'd/m/Y H:i');
        $dataFinalFormat = date_format(date_create($dataFim), 'd/m/Y H:i');

        $factura = Factura::where('empresa_id', auth()->user()->empresa_id)
            ->where('anulado', 1)->where('tipo_documento', 1)->whereBetween('created_at', [$dataInicio, $dataFim])->get();

        if (count($factura) > 0) {
            $operador = "Todos operadores";
            $filename = 'relatoriosDeVendaXls';
            $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;

            $reportController = new ReportShowController('xls');
            $report = $reportController->show(
                [
                    'report_file' => $filename,
                    'report_jrxml' => $filename . '.jrxml',
                    'report_parameters' => [
                        'empresa_id' => auth()->user()->empresa_id,
                        'data_inicio' => $dataInicio,
                        'data_fim' => $dataFim,
                        'user_id' => 0,
                        'dataInicioFormat' => $dataInicioFormat,
                        'dataFinalFormat' => $dataFinalFormat,
                        'operador' => $operador,
                        'logotipo' => $logotipo
                    ]

                ]
            );

            $this->dispatchBrowserEvent('printLink', ['data' => $report['filename']]);
            unlink($report['filename']);
            flush();
        } else {
            $this->confirm('Não existe documento neste intervalo/ou documento anulado neste intervalo', [
                'showConfirmButton' => 'OK',
                'showCancelButton' => false,
                'icon' => 'warning',
            ]);
            return;
        }
    }

    public function printRelatorioVendaCash($format)
    {

        $request = $this->venda;
        $rules = [
            'venda.dataInicio' => ["required", function ($attribute, $value, $fail) use ($request) {
                if ($request['dataInicio'] > $request['dataFim']) {
                    $fail('data inicial é maior que a final');
                    return;
                }
            }],
            'venda.dataFim' => 'required',
        ];
        $messages = [
            'venda.dataInicio.required' => 'Informe as duas datas',
            'venda.dataFim.required' => 'Informe as duas datas',
        ];

        $this->validate($rules, $messages);

        $dataInicio = str_replace("T", " ", $this->venda['dataInicio']) . ":00";
        $dataFim = str_replace("T", " ", $this->venda['dataFim']) . ":00";

        $dataInicioFormat = date_format(date_create($dataInicio), 'd/m/Y H:i');
        $dataFinalFormat = date_format(date_create($dataFim), 'd/m/Y H:i');


        $factura = Factura::where('empresa_id', auth()->user()->empresa_id)
            ->where('anulado', 1)->where('tipo_documento', 1)->whereBetween('created_at', [$dataInicio, $dataFim])->get();

        if (count($factura) > 0) {

            $operador = "Todos operadores";
            $filename = 'relatoriosDeVendaCash';
            $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;


            $reportController = new ReportShowController();
            $report = $reportController->show(
                [
                    'report_file' => $filename,
                    'report_jrxml' => $filename . '.jrxml',
                    'report_parameters' => [
                        'empresa_id' => auth()->user()->empresa_id,
                        'data_inicio' => $dataInicio,
                        'data_fim' => $dataFim,
                        'user_id' => 0,
                        'dataInicioFormat' => $dataInicioFormat,
                        'dataFinalFormat' => $dataFinalFormat,
                        'operador' => $operador,
                        'logotipo' => $logotipo
                    ]

                ]
            );
            $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
            unlink($report['filename']);
            flush();
        } else {
            $this->confirm('Não existe documento neste intervalo/ou documento anulado neste intervalo', [
                'showConfirmButton' => 'OK',
                'showCancelButton' => false,
                'icon' => 'warning',
            ]);
            return;
        }
    }

    public function printRelatorioVendaMulticaixa($format)
    {
        $request = $this->venda;
        $rules = [
            'venda.dataInicio' => ["required", function ($attribute, $value, $fail) use ($request) {
                if ($request['dataInicio'] > $request['dataFim']) {
                    $fail('data inicial é maior que a final');
                    return;
                }
            }],
            'venda.dataFim' => 'required',
        ];
        $messages = [
            'venda.dataInicio.required' => 'Informe as duas datas',
            'venda.dataFim.required' => 'Informe as duas datas',
        ];

        $this->validate($rules, $messages);


        $dataInicio = str_replace("T", " ", $this->venda['dataInicio']);
        $dataFim = str_replace("T", " ", $this->venda['dataFim']);


        $dataInicioFormat = date_format(date_create($dataInicio), 'd/m/Y H:i');
        $dataFinalFormat = date_format(date_create($dataFim), 'd/m/Y H:i');


        $factura = Factura::where('empresa_id', auth()->user()->empresa_id)
            ->where('anulado', 1)->where('tipo_documento', 1)->whereBetween('created_at', [$dataInicio, $dataFim])->get();

    
        if (count($factura) > 0) {

            $operador = "Todos operadores";
            $filename = 'relatoriosDeVendaMulticaixa';
            $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;


            $reportController = new ReportShowController();
            $report = $reportController->show(
                [
                    'report_file' => $filename,
                    'report_jrxml' => $filename . '.jrxml',
                    'report_parameters' => [
                        'empresa_id' => auth()->user()->empresa_id,
                        'data_inicio' => $dataInicio,
                        'data_fim' => $dataFim,
                        'dataInicioFormat' => $dataInicioFormat,
                        'dataFinalFormat' => $dataFinalFormat,
                        'operador' => $operador,
                        'logotipo' => $logotipo
                    ]

                ]
            );
            $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
            unlink($report['filename']);
            flush();
        } else {
            $this->confirm('Não existe documento neste intervalo/ou documento anulado neste intervalo', [
                'showConfirmButton' => 'OK',
                'showCancelButton' => false,
                'icon' => 'warning',
            ]);
            return;
        }
    }

    public function printRelatorioVenda($format)
    {
        $request = $this->venda;
        $rules = [
            'venda.dataInicio' => ["required", function ($attribute, $value, $fail) use ($request) {
                if ($request['dataInicio'] > $request['dataFim']) {
                    $fail('data inicial é maior que a final');
                    return;
                }
            }],
            'venda.dataFim' => 'required',
        ];
        $messages = [
            'venda.dataInicio.required' => 'Informe as duas datas',
            'venda.dataFim.required' => 'Informe as duas datas',
        ];

        $this->validate($rules, $messages);

        $dataInicio = str_replace("T", " ", $this->venda['dataInicio']);
        $dataFim = str_replace("T", " ", $this->venda['dataFim']);

        $dataInicioFormat = date_format(date_create($dataInicio), 'd/m/Y H:i');
        $dataFinalFormat = date_format(date_create($dataFim), 'd/m/Y H:i');


        $factura = Factura::where('empresa_id', auth()->user()->empresa_id)
            ->where('anulado', 1)->where('tipo_documento', 1)->whereBetween('created_at', [$dataInicio, $dataFim])->get();

        if (count($factura) > 0) {

            $operador = "Todos operadores";
            $filename = $format == 'pdf' ? 'relatoriosDeVenda' : 'relatoriosDeVendaXls';
            $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;


            $reportController = new ReportShowController();
            $report = $reportController->show(
                [
                    'report_file' => $filename,
                    'report_jrxml' => $filename . '.jrxml',
                    'report_parameters' => [
                        'empresa_id' => auth()->user()->empresa_id,
                        'data_inicio' => $dataInicio,
                        'data_fim' => $dataFim,
                        'dataInicioFormat' => $dataInicioFormat,
                        'dataFinalFormat' => $dataFinalFormat,
                        'operador' => $operador,
                        'logotipo' => $logotipo
                    ]

                ]
            );
            $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
            unlink($report['filename']);
            flush();

            /*
            $reportController = new ReportsController($format);

            $file = $reportController->show(
                [
                    'report_file' => $filename,
                    'report_jrxml' => $filename . '.jrxml',
                    'report_parameters' => [
                        'empresa_id' => auth()->user()->empresa_id,
                        'data_inicio' => $dataInicio,
                        'data_fim' => $dataFim,
                        'user_id' => 0,
                        'dataInicioFormat' => $dataInicioFormat,
                        'dataFinalFormat' => $dataFinalFormat,
                        'operador' => $operador,
                        'logotipo' => $logotipo
                    ]
                ]
            );


            return response()->streamDownload(function () use ($file) {
                echo response()::file($file)->getContent();
            }, 'myFile.pdf');
            */
        } else {
            $this->confirm('Não existe documento neste intervalo/ou documento anulado neste intervalo', [
                'showConfirmButton' => 'OK',
                'showCancelButton' => false,
                'icon' => 'warning',
            ]);
            return;
        }


        //158
        // DATA INICIO =  2022-09-05 13:05
        // DATA FIM =  2022-09-05 13:05

        // DATA INICIO =  05/09/2022 13:05
        // DATA FIM =  05/09/2022 13:05
        // Todos operadores
        // C:\laragon\www\appmutuenegociosv1\public/upload//utilizadores/cliente/LSiFuIFEP1qhJ4mcpYcXpfidlenFnrmTKDq7Lvm1.jpg


    }


    public function printRelatorioExistenciaStock()
    {
        
        $reportController = new ReportsController();

        $logotipo = public_path() . "/upload//" . auth()->user()->empresa->logotipo;

        return $reportController->show(
            [
                'report_file' => 'relatoriosExistenciaStocks',
                'report_jrxml' => 'relatoriosExistenciaStocks.jrxml',
                'report_parameters' => [
                    'nomeEmpresa' => auth()->user()->empresa->nome,
                    'emailEmpresa' => auth()->user()->empresa->email,
                    'nifEmpresa' => auth()->user()->empresa->nif,
                    'telefoneEmpresa' => auth()->user()->empresa->pessoal_Contacto,
                    'enderecoEmpresa' => auth()->user()->empresa->endereco,
                    'empresa_id' => auth()->user()->empresa_id,
                    'logotipo' => $logotipo,
                    'centroCustoId' => session()->get('centroCustoId')

                ]

            ]
        );

        // return response()->streamDownload(function () use ($file) {
        //     echo response()::file($file)->getContent();
        // }, 'myFile.pdf');
    }
}
