<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Application\UseCase\Empresa\Bancos\GetBancos;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoOutroServico;
use App\Application\UseCase\Empresa\Faturacao\GetTipoDocumentoByFaturacao;
use App\Application\UseCase\Empresa\Faturacao\SimuladorOutroServicoAeroporto;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamentoByFaturacao;
use App\Application\UseCase\Empresa\Operacao\EmitirNotaCreditoRetificacao;
use App\Application\UseCase\Empresa\Pais\GetPaises;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloTipoServico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemOutroServico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemServicoComercial;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaOutroServico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaServicoComercial;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura;
use App\Models\empresa\Factura as FaturaDatabase;
use App\Models\empresa\Moeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RetificacaoDocumentoFaturaOutroServicoCreateController extends Component
{
    use LivewireAlert;
    use TraitPrintNotaCredito;


    public $clientes;
    public $bancos;
    public $moedas;
    public $empresa;
    public $item = [
        'produto' => null,
        'quantidade' => null,
        'desconto' => 0
    ];
    public $observacao = null;

    public $formasPagamentos = [];

    public $fatura = [
        'moeda' => null,
        'numeroAeronave' => null,
        'tipoDocumento' => 3, //Fatura proforma
        'formaPagamentoId' => null, //Fatura proforma
        'moedaPagamento' => 'AOA',
        'observacao' => null,
        'isencaoIVA' => false,
        'retencao' => false,
        'taxaRetencao' => 0,
        'valorRetencao' => 0,
        'nomeProprietario' => null,
        'clienteId' => null,
        'nomeCliente' => null,
        'telefoneCliente' => null,
        'nifCliente' => null,
        'emailCliente' => null,
        'enderecoCliente' => null,
        'taxaIva' => 0,
        'cambioDia' => 0,
        'contraValor' => 0,
        'valorliquido' => 0,
        'valorDesconto' => 0,
        'valorIliquido' => 0,
        'valorImposto' => 0,
        'total' => 0,
        'items' => []
    ];
    public $servicos;
    public $paises;
    public $tiposDocumentos;

    protected $listeners = ['selectedItem'];


    public function selectedItem($item)
    {
        if ($item['atributo'] == 'clienteId') {
            $this->updatedFaturaClienteId($item['valor']);
        }
        $this->fatura[$item['atributo']] = $item['valor'];
    }

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function updatedFaturaIsencaoIVA()
    {
        $this->fatura['taxaRetencao'] = 0;
        $this->fatura['valorRetencao'] = 0;
        $this->fatura['taxaIva'] = 0;
        $this->fatura['cambioDia'] = 0;
        $this->fatura['contraValor'] = 0;
        $this->fatura['valorliquido'] = 0;
        $this->fatura['valorDesconto'] = 0;
        $this->fatura['valorIliquido'] = 0;
        $this->fatura['valorImposto'] = 0;
        $this->fatura['moeda'] = null;
        $this->fatura['total'] = 0;
        $this->fatura['items'] = [];
    }

    public function updatedFaturaRetencao()
    {

        $this->fatura['taxaRetencao'] = 0;
        $this->fatura['valorRetencao'] = 0;
        $this->fatura['taxaIva'] = 0;
        $this->fatura['cambioDia'] = 0;
        $this->fatura['contraValor'] = 0;
        $this->fatura['valorliquido'] = 0;
        $this->fatura['valorDesconto'] = 0;
        $this->fatura['valorIliquido'] = 0;
        $this->fatura['valorImposto'] = 0;
        $this->fatura['moeda'] = null;
        $this->fatura['total'] = 0;
        $this->fatura['items'] = [];
    }

    public function updatedFaturaTipoDocumento($tipoDocumento)
    {
        if ($tipoDocumento == 1) {
            $this->fatura['formaPagamentoId'] = 1;
            $getFormaPagamentoByFaturacao = new GetFormasPagamentoByFaturacao(new DatabaseRepositoryFactory());
            $this->formasPagamentos = $getFormaPagamentoByFaturacao->execute();
        } else {
            $this->fatura['formaPagamentoId'] = null;
            $this->formasPagamentos = [];
        }
        $simuladorFaturaCarga = new SimuladorOutroServicoAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaCarga->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);
    }

    public function updatedFaturaFormaPagamentoId($formaPagamentoId)
    {
        $this->fatura['formaPagamentoId'] = $formaPagamentoId;
        $simuladorFaturaCarga = new SimuladorOutroServicoAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaCarga->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);
    }

    public function updatedFaturaClienteId($clienteId)
    {
        $cliente = DB::table('clientes')->where('id', $clienteId)
            ->where('empresa_id', auth()->user()->empresa_id)->first();
        $this->fatura['clienteId'] = $cliente->id;
        $this->fatura['nomeCliente'] = $cliente->nome;
        $this->fatura['telefoneCliente'] = $cliente->telefone_cliente;
        $this->fatura['nifCliente'] = $cliente->nif;
        $this->fatura['emailCliente'] = $cliente->email;
        $this->fatura['enderecoCliente'] = $cliente->endereco;

    }

    public function mount($faturaId)
    {
        $this->setarDadosFatura($faturaId);

        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $this->fatura['moeda'] = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;

//        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
//        $this->clientes = $getClientes->execute();

        $this->empresa = auth()->user()->empresa;

        $getBancos = new GetBancos(new DatabaseRepositoryFactory());
        $this->bancos = $getBancos->execute();


        $getProdutos = new GetProdutoPeloTipoServico(new DatabaseRepositoryFactory());
        $this->servicos = $getProdutos->execute(3);

        $getPaises = new GetPaises(new DatabaseRepositoryFactory());
        $this->paises = $getPaises->execute();

        $getTiposDocumentos = new GetTipoDocumentoByFaturacao(new DatabaseRepositoryFactory());
        $this->tiposDocumentos = $getTiposDocumentos->execute();
        $this->moedas = Moeda::get();
    }
    public function updatedObservacao($observacao)
    {
        $this->fatura['observacao'] = $observacao;

    }

    public function setarDadosFatura($faturaId)
    {
        $fatura = FaturaDatabase::with(['facturas_items'])
            ->where('id', $faturaId)
            ->where('anulado', '!=', 'Y')
            ->where('retificado', '!=', 'Y')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();
        if (!$fatura) return redirect()->route('facturasOutrosServico.index');


        $this->numeracaoFatura = $fatura['numeracaoFactura'];
        $this->faturaId = $fatura['id'];
        $this->tipoFatura = $fatura['tipoFatura'];
        $input = (object)$fatura;
        $isencaoIVA = $input->isencaoIVA == 'Y' ? true : false;
        $retencao = $input->taxaRetencao > 0 ? true : false;

        if ($input->isencaoIVA == 'Y') {
            $taxaIva = 0;
        } else {
            $taxaIva = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $taxaIva = (float)$taxaIva->execute('valor_iva_aplicado')->valor;
        }

        $faturaOutroServico = new FaturaOutroServico(
            $input->numeroAeronave,
            $input->tipoDocumento,
            $input->formaPagamentoId,
            $input->observacao,
            $input->nomeProprietario,
            $input->clienteId,
            $input->nome_do_cliente,
            $input->telefone_cliente,
            $input->nif_cliente,
            $input->email_cliente,
            $input->endereco_cliente,
            $input->taxaIva,
            $input->moeda,
            $input->moedaPagamento,
            $isencaoIVA,
            $retencao,
            $input->taxaRetencao,
            $input->cambioDia
        );

        foreach ($input->facturas_items as $item) {
            $item = (object)$item;
            $faturaItemOutroServico = new FaturaItemOutroServico(
                $item->produtoId,
                $item->nomeProduto,
                $item->desconto,
                $item->taxa,
                $taxaIva,
                $item->quantidade,
                $input->cambioDia
            );
            $faturaOutroServico->addItem($faturaItemOutroServico);
        }
        $this->fatura = $this->conversorModelParaArray($faturaOutroServico);
    }

    public function render()
    {
        $this->clientes = DB::table('clientes')->where('empresa_id', auth()->user()->empresa_id)->get();
        return view("empresa.operacao.documentosRetificacaoFaturaOutroServicoCreate");
    }

    public function removeCart($item)
    {
        foreach ($this->fatura['items'] as $key => $itemCart) {
            if ($itemCart['produtoId'] == $item['produtoId']) {
                unset($this->fatura['items'][$key]);
            }
        }
        $this->calculadoraTotal();
    }

    public function addCart()
    {

        if (!$this->item['produto']) {
            $this->confirm('Seleciona o serviço', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $produtoData = json_decode($this->item['produto']);
        $rules = [
            'item.produto' => 'required',
            'item.quantidade' => ['required', function ($attr, $qtd, $fail) {
                if ($qtd <= 0) {
                    $fail('campo obrigatório');
                }
            }]
        ];
        $messages = [
            'item.produto.required' => 'campo obrigatório',
            'item.quantidade.required' => 'campo obrigatório'
        ];
        $this->validate($rules, $messages);

        $key = $this->isCart($produtoData);
        if ($key !== false) {
            $this->confirm('O serviço já foi adicionado', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $produto = json_decode($this->item['produto']);
        $this->item['nomeProduto'] = $produto->designacao;
        $this->item['produtoId'] = $produto->id;
        $this->item['produto'] = $this->item['produto'];
        $this->item['precoVenda'] = $produto->preco_venda;
        $this->item['quantidade'] = (double)$this->item['quantidade'];
        $this->fatura['items'][] = (array)$this->item;
        $this->calculadoraTotal();
    }

    public function calculadoraTotal()
    {
        $simuladorFaturaAeronautico = new SimuladorOutroServicoAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaAeronautico->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);

    }

    private function isCart($item)
    {
        $items = collect($this->fatura['items']);
        $posicao = $items->search(function ($produto) use ($item) {
            return $produto['produtoId'] === $item->id;
        });
        return $posicao;
    }

    private function conversorModelParaArray(FaturaOutroServico $output)
    {
        $fatura = [
            'nomeProprietario' => $output->getProprietario(),
            'clienteId' => $output->getClienteId(),
            'observacao' => $output->getObservacao(),
            'nomeCliente' => $output->getNomeCliente(),
            'telefoneCliente' => $output->getTelefoneCliente(),
            'nifCliente' => $output->getNifCliente(),
            'emailCliente' => $output->getEmailCliente(),
            'enderecoCliente' => $output->getEnderecoCliente(),
            'numeroAeronave' => $output->getNumeroAeronave(),
            'tipoDocumento' => $output->getTipoDocumento(), //Fatura recibo
            'formaPagamentoId' => $output->getFormaPagamentoId(),
            'isencaoIVA' => $output->getIsencaoIVA(),
            'retencao' => $output->getRetencao(),
            'taxaRetencao' => $output->getTaxaRetencao(),
            'valorRetencao' => $output->getValorRetencao(),
            'taxaIva' => $output->getTaxaIva(),
            'cambioDia' => $output->getCambioDia(),
            'contraValor' => $output->getContraValor(),
            'valorliquido' => $output->getValorLiquido(),
            'valorDesconto' => $output->getDesconto(),
            'valorIliquido' => $output->getValorIliquido(),
            'valorImposto' => $output->getValorImposto(),
            'total' => $output->getTotal(),
            'moeda' => $output->getMoeda(),
            'moedaPagamento' => $output->getMoedaPagamento(),
            'items' => []
        ];
        foreach ($output->getItems() as $item) {
            array_push($fatura['items'], [
                'produtoId' => $item->getProdutoId(),
                'taxa' => $item->getPrecoProduto(),
                'nomeProduto' => $item->getNomeProduto(),
                'quantidade' => $item->getQuantidade(),
                'precoVenda' => $item->getPrecoProduto(),
                'valorIva' => $item->getValorIva(),
                'taxaIva' => $item->getTaxaIva(),
                'cambioDia' => $item->getCambioDia(),
                'desconto' => $item->getDesconto(),
                'valorDesconto' => $item->getValorDesconto(),
                'total' => $item->getTotal(),
                'totalIva' => $item->getTotalIva(),
            ]);
        }
        return $fatura;
    }

    public function emitirDocumento()
    {
        $rules = [
            'fatura.clienteId' => 'required',
            'fatura.numeroAeronave' => [function ($attr, $numeroAeronave, $fail) {
                if (count($this->fatura['items']) > 0) {
                    foreach ($this->fatura['items'] as $item) {
                        if (in_array($item['produtoId'], [47, 48, 49, 50]) && !$numeroAeronave) {
                            $fail('campo obrigatório');
                        }
                    }
                }
            }]
        ];
        $messages = [
            'fatura.clienteId.required' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);

        if (count($this->fatura['items']) <= 0) {
            $this->confirm('Adiciona os serviços', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        if (!$this->fatura['clienteId']) {
            $this->confirm('Seleciona o cliente', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $emitirDocumento = new EmitirNotaCreditoRetificacao(new DatabaseRepositoryFactory());
        $this->fatura['facturaId'] = $this->faturaId;
        $this->fatura['tipoFatura'] = $this->tipoFatura;
        $notaCreditoId = $emitirDocumento->execute(new Request($this->fatura));

        $this->printNotaCredito($notaCreditoId);
    }


    public function printFatura($facturaId)
    {
        $factura = Factura::with(['notaCredito'])->where('id', $facturaId)->first();

//        $getParametro = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
//        $parametro = $getParametro->execute('tipoImpreensao');

        $filename = "WinmarketOutroServico";
//        if ($parametro->valor == 'A5') {
//            $filename = "Winmarket_A5";
//        }
        if ($factura->tipo_documento == 3) { //proforma
            $logotipo = public_path() . '/upload/_logo_ATO_vertical_com_TAG_color.png';
        } else {
            $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        }
        $DIR_SUBREPORT = "/upload/documentos/empresa/modelosFacturas/a4/";
        $DIR = public_path() . "/upload/documentos/empresa/modelosFacturas/a4/";
        $reportController = new ReportShowController('pdf', $DIR_SUBREPORT);

        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    "viaImpressao" => 1,
                    "logotipo" => $logotipo,
                    "empresa_id" => auth()->user()->empresa_id,
                    "facturaId" => $facturaId,
                    "dirSubreportBanco" => $DIR,
                    "dirSubreportTaxa" => $DIR,
                    "tipo_regime" => auth()->user()->empresa->tipo_regime_id,
                    "nVia" => 1,
                    "DIR" => $DIR
                ]
            ], "pdf", $DIR_SUBREPORT
        );


        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        // $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

    public function resetField()
    {
        $this->fatura = [
            'moeda' => null,
            'tipoDocumento' => 3, //Fatura proforma
            'formaPagamentoId' => null, //Fatura proforma
            'moedaPagamento' => 'AOA',
            'observacao' => null,
            'isencaoIVA' => false,
            'retencao' => false,
            'taxaRetencao' => 0,
            'valorRetencao' => 0,
            'nomeProprietario' => null,
            'clienteId' => null,
            'nomeCliente' => null,
            'telefoneCliente' => null,
            'nifCliente' => null,
            'emailCliente' => null,
            'enderecoCliente' => null,
            'taxaIva' => 0,
            'cambioDia' => 0,
            'contraValor' => 0,
            'valorliquido' => 0,
            'valorDesconto' => 0,
            'valorIliquido' => 0,
            'valorImposto' => 0,
            'total' => 0,
            'items' => []
        ];
        $this->formasPagamentos = [];

    }
}
