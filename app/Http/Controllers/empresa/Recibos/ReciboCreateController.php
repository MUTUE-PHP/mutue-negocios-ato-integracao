<?php

namespace App\Http\Controllers\empresa\Recibos;

use App\Application\UseCase\Empresa\Faturas\GetFaturaPelaNumeracao;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormaPagamentoEmitirRecibo;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Recibos\EmitirRecibo;
use App\Application\UseCase\Empresa\Recibos\GetTotalDebitadoFatura;
use App\Application\UseCase\Empresa\Recibos\SimuladorRecibo;
use App\Http\Controllers\empresa\ReportShowController;
use App\Http\Controllers\TraitLogAcesso;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura;
use App\Models\empresa\Recibos;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use illuminate\Support\Str;

class ReciboCreateController extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    use TraitLogAcesso;
    public $recibo = [
        'numeracaoFactura' => null,
        'clienteId' => null,
        'nomeCliente' => null,
        'nifCliente' => null,
        'telefoneCliente' => null,
        'emailCliente' => null,
        'enderecoCliente' => null,
        'formaPagamentoId' => 1,
        'totalFatura' => null,
        'totalDebitar' => 0,
        'totalDebitado' => 0,
        'totalEntregue' => 0,
        "dataOperacao" => null,
        "numeroOperacaoBancaria" => null,
        "comprovativoBancario" => null,
        'observacao' => null,
    ];
    public $anexo;
    public $isDisabled = 1;
    public $limiteDate;

    public $formaPagamentos = [];


    private $reciboRepository;
    private $clienteRepository;
    private $facturaRepository;
    private $formaPagamentoRepository;

    protected $listeners = ['selected' => 'selected'];


    public function mount()
    {
        $this->limiteDate = date('Y-m-d');
        $getFormaPagamento = new GetFormaPagamentoEmitirRecibo(new DatabaseRepositoryFactory());
        $this->formaPagamentos = $getFormaPagamento->execute();

    }
    public function render()
    {
        return view('empresa.recibos.create');
    }
    public function updatedReciboNumeracaoFactura($numeracaoFatura)
    {
        $getFatura = new GetFaturaPelaNumeracao(new DatabaseRepositoryFactory());
        $fatura = $getFatura->execute($numeracaoFatura);
        if ($fatura && strlen($numeracaoFatura) > 8) {
            $fatura = (object) $fatura;
            $getTotalDebitadoFatura = new GetTotalDebitadoFatura(new DatabaseRepositoryFactory());
            $totalEntregue = $getTotalDebitadoFatura->execute($fatura->id);

            $data = [
                'clienteId' => $fatura->clienteId,
                'nomeCliente' => $fatura->nome_do_cliente,
                'nifCliente' => $fatura->nif_cliente,
                'telefoneCliente' => $fatura->telefone_cliente,
                'emailCliente' => $fatura->email_cliente,
                'enderecoCliente' => $fatura->endereco_cliente,
                'anulado' => 1,
                'totalEntregue' => $totalEntregue,
                'totalImposto' => $fatura->valorImposto,
                'facturaId' => $fatura->id,
                'totalFatura' => $fatura->total,
                'formaPagamentoId' => $this->recibo['formaPagamentoId'],
                "dataOperacao" => $this->recibo['dataOperacao'],
                "numeroOperacaoBancaria" => $this->recibo['numeroOperacaoBancaria'],
                "comprovativoBancario" => $this->recibo['comprovativoBancario'],
                'observacao' => $this->recibo['observacao'],
                'numSequenciaRecibo' => null
            ];

            $simuladorRecibo = new SimuladorRecibo(new DatabaseRepositoryFactory());
            $recibo = $simuladorRecibo->execute($data);

            $this->recibo['clienteId'] = $recibo->getClienteId();
            $this->recibo['nomeCliente'] = $recibo->getNomeCliente();
            $this->recibo['nifCliente'] = $recibo->getNifCliente();
            $this->recibo['telefoneCliente'] = $recibo->getTelefoneCliente();
            $this->recibo['emailCliente'] = $recibo->getEmailCliente();
            $this->recibo['enderecoCliente'] = $recibo->getEnderecoCliente();
            $this->recibo['anulado'] = $recibo->getAnulado();
            $this->recibo['facturaId'] = $recibo->getFacturaId();
            $this->recibo['totalFatura'] = $recibo->getTotalFatura();
            $this->recibo['totalImposto'] = $recibo->getTotalImposto();
            $this->recibo['formaPagamentoId'] = $recibo->getFormaPagamentoId();
            $this->recibo['numeroOperacaoBancaria'] = $recibo->GetNumeroOperacaoBancaria();
            $this->recibo['dataOperacao'] = $recibo->GetDataOperacao();
            $this->recibo['comprovativoBancario'] = $recibo->GetcomprovativoBancario();
            $this->recibo['observacao'] = $recibo->getObservacao();
            $this->recibo['totalDebitar'] = $recibo->getTotalDebitar();
            $this->recibo['totalEntregue'] = $recibo->getTotalDebitar();
            $this->recibo['totalDebitado'] = $recibo->getTotalDebitado();
        }else{
            $this->recibo = [
                'numeracaoFactura' => $this->recibo['numeracaoFactura'],
                'clienteId' => null,
                'nomeCliente' => null,
                'nifCliente' => null,
                'telefoneCliente' => null,
                'emailCliente' => null,
                'enderecoCliente' => null,
                'formaPagamentoId' => 1,
                'totalFatura' => null,
                'totalDebitar' => 0,
                'totalDebitado' => 0,
                'totalEntregue' => 0,
                'numeroOperacaoBancaria' => null,
                'dataOperacao' => null,
                'comprovativoBancario' => null,
                'observacao' => null,
            ];
        }
    }

    public function emitirRecibo()
    {
        $rules = [
            'recibo.numeroOperacaoBancaria' => [$this->isDisabled != 1? 'required': ''],
              'recibo.comprovativoBancario' =>  'mimes:jpg,png,jpeg,png,pdf|max:1024',
              'recibo.comprovativoBancario' => [$this->isDisabled !=1? 'required': ''],
            'recibo.numeracaoFactura' => ['required'],
            'recibo.totalEntregue' => ['required', function ($attr, $totalEntregue, $fail) {
                if ($totalEntregue <= 0) {
                    $fail("Informe o valor entregue");
                } else if ($totalEntregue > $this->recibo['totalDebitar']) {
                    $fail("O valor entregue não deve ser maior ao total a debitar");
                }
            }]
        ];
        $messages = [
            'recibo.numeracaoFactura.required' => 'Informe o numeração do documento',
            'recibo.totalEntregue.required' => 'Informe o valor entregue',
            'recibo.numeroOperacaoBancaria.required' => 'Informe o número de operação bancária',
            'recibo.comprovativoBancario.mimes' => 'O formato do arquivo não é válido',
            'recibo.comprovativoBancario.max' => 'Anexo demasiado grande',
            'recibo.comprovativoBancario.required' => 'Introduza o anexo'



        ];
        $this->validate($rules, $messages);

        try {
            $emitirRecibo = new EmitirRecibo(new DatabaseRepositoryFactory());
            $recibo = $emitirRecibo->execute($this->recibo);
        }catch (\Exception $exception){


        }



        $this->logAcesso();

        $logotipo = public_path() . '/upload/AtoNegativo1.png';

        $getParametro = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $parametro = $getParametro->execute('tipoImpreensao');

        $filename = "recibos";
        if ($parametro->valor == 'A5') {
            $filename = "recibos_A5";
        }

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'viaImpressao' => 1,
                    'empresa_id' => auth()->user()->empresa_id,
                    'recibo_id' => $recibo->id,
                    'factura_id' => $recibo->facturaId,
                    'logotipo' => $logotipo
                ]
            ]
        );
        $this->resetField();
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
    public function resetField(){
        $this->recibo = [
            'numeracaoFactura' => null,
            'clienteId' => null,
            'nomeCliente' => null,
            'nifCliente' => null,
            'telefoneCliente' => null,
            'emailCliente' => null,
            'enderecoCliente' => null,
            'formaPagamentoId' => 1,
            'totalFatura' => null,
            'totalDebitar' => 0,
            'totalDebitado' => 0,
            'totalEntregue' => 0,
            'numeroOperacaoBancaria' => null,
            'dataOperacao' => null,
            'comprovativoBancario' => null,
            'observacao' => null,
        ];
    }

    public function updatedReciboFormaPagamentoId($value)
    {
            $this->isDisabled= $value;

    }
}
