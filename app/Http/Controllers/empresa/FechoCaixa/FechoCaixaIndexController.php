<?php

namespace App\Http\Controllers\empresa\FechoCaixa;

use App\Application\UseCase\Empresa\Fornecedores\GetFornecedores;
use App\Domain\Entity\Empresa\CentrosDeCusto\CentroCusto;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use App\Models\empresa\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FechoCaixaIndexController extends Component
{
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

    public $data;

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
        $this->operadores = User::where('empresa_id', auth()->user()->empresa_id)->get();
        if (!$this->operadores) {
            return redirect()->back();
        }
    }

    public function boot()
    {
        $this->venda['dataFim'] = NULL;
        $this->venda['dataInicio'] = NULL;
    }

    public function render()
    {
        return view('empresa.fechoCaixa.index');
    }
    public function imprimirFechoCaixa()
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
            'operadorSelecionado' => 'required',
        ];
        $messages = [
            'data_inicio.required' => 'Informe a data Inicial',
            'data_fim.required' => 'Informe a data Final',
            'operadorSelecionado.required' => 'Informe o operador',
        ];
        $this->validate($rules, $messages);
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "relatorioFechoCaixa";

        $qtdFt = DB::table('facturas')
            ->where('user_id', auth()->user()->id)
            ->where('tipoDocumento', 2)
            ->where(function($query){
                $query->where('created_at', '>=', $this->data_inicio)
                    ->where('created_at', '<=', $this->data_fim);
            })
            ->where('anulado', 'N')
            ->count();

        $qtdFr = DB::table('facturas')
            ->where('user_id', auth()->user()->id)
            ->where('tipoDocumento', 1)
            ->where(function($query){
                $query->where('created_at', '>=', $this->data_inicio)
                ->where('created_at', '<=', $this->data_fim);
            })
            ->where('anulado', 'N')
            ->count();

        $qtdFp = DB::table('facturas')
            ->where('user_id', auth()->user()->id)
            ->where('tipoDocumento', 3)
            ->where('anulado', 'N')
            ->where(function($query){
                $query->where('created_at', '>=', $this->data_inicio)
                    ->where('created_at', '<=', $this->data_fim);
            })
            ->count();

        $qtdRc = DB::table('recibos')
            ->where('userId', auth()->user()->id)
            ->where('anulado', 'N')
            ->where(function($query){
                $query->where('created_at', '>=', $this->data_inicio)
                    ->where('created_at', '<=', $this->data_fim);
            })
            ->count();




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
                    'qtdFt' => $qtdFt,
                    'qtdFr' => $qtdFr,
                    'qtdFp' => $qtdFp,
                    'qtdRc' => $qtdRc,
                ]
            ]
        );


        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();


        // $dataInicio = $this->data . " 06:00";
        // $dataFim = $this->data . " 23:59";
        // $formatoPeriodo = date_format(date_create($this->data),"d/m/Y"). " 06:00 à 23:59";
        // $rules = [
        //     'data' => ["required", function ($attr, $value, $fail) use ($dataInicio, $dataFim) {
        //         $countFactura =  DB::table('facturas')->where('empresa_id', auth()->user()->empresa_id)
        //             ->whereDate('created_at', '=', $this->data)
        //             ->get();
        //         if (count($countFactura) <= 0) {
        //             $fail("Não existe vendas neste data");
        //         }
        //     }],
        // ];
        // $messages = [
        //     'data.required' => 'Informe a data'
        // ];

        // $this->validate($rules, $messages);

        // $filename = 'fechoCaixaPorDataDefinidasTodosOperadores';

        // $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;

        // $DIR = public_path() . '/upload/documentos/empresa/relatorios/';

        // // dd(auth()->user()->empresa_id);

        // // empresaId = 158
        // // data inicio = 2022-02-21 08:00
        // // data fim = 2022-02-23 21:00
        // // logotipo = C:\laragon\www\mutue-negocios\public/upload//utilizadores/cliente/laAJKtYOSjaLBTSjPlhDFfwmincv6pGBxAtuCThh.png
        // // formatoPeriodo = 22-02-2022 08:00 as 21:00
        // // DIR = C:\laragon\www\mutue-negocios\public/upload/documentos/empresa/relatorios//


        // //MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA
        // //Rua Nossa senhora da Muxima, nº 10 - 8º andar
        // //922969192
        // //5000977381
        // //148
        // //638
        // //2023-12-09 01:30
        // //2023-12-09 23:59
        // //C:\laragon\www\api-mutue-negocio\public/upload//utilizadores/cliente/IrcsAYqRR5UnHJ0TkABvqkw5QK1OZQNImO6Acn8U.png
        // //09/12/2023 06:00 à 23:59
        // //C:\laragon\www\api-mutue-negocio\public/upload/documentos/empresa/relatorios/
        // $reportController = new ReportShowController();
        // $report = $reportController->show(
        //     [
        //         'report_file' => $filename,
        //         'report_jrxml' => $filename . '.jrxml',
        //         'report_parameters' => [
        //             'nomeEmpresa' => auth()->user()->empresa->nome,
        //             'enderecoEmpresa' => auth()->user()->empresa->endereco,
        //             'telefoneEmpresa' => auth()->user()->empresa->pessoal_Contacto,
        //             'nifEmpresa' => auth()->user()->empresa->nif,
        //             'empresa_id' => auth()->user()->empresa_id,
        //             'userId' => auth()->user()->id,
        //             'data_inicio' => $dataInicio,
        //             'data_fim' => $dataFim,
        //             'logotipo' => $logotipo,
        //             'formatoPeriodo' => $formatoPeriodo,
        //             'DIR' => $DIR
        //         ]
        //     ]
        // );
        // $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        // unlink($report['filename']);
        // flush();
    }
}
