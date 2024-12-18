<?php

namespace App\Http\Controllers\empresa\Faturacao;

use App\Application\UseCase\Empresa\Bancos\GetBancos;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroporto;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoAeronave;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoCarga;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoServicoComercial;
use App\Application\UseCase\Empresa\Faturacao\GetTipoDocumentoByFaturacao;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaAeronauticoAeroporto;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaCargaAeroporto;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaServicoComercial;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamentoByFaturacao;
use App\Application\UseCase\Empresa\mercadorias\GetTiposMercadorias;
use App\Application\UseCase\Empresa\Pais\GetPaises;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloCentroCustoId;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloTipoServico;
use App\Application\UseCase\Empresa\Produtos\GetProdutos;
use App\Application\UseCase\Empresa\TiposServicos\GetTiposServicos;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaCarga;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaServicoComercial;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Moeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EmissaoFaturaServicoComercialControllerCopia extends Component
{
    use LivewireAlert;

    public $clientes;
    public $bancos;
    public $moedas;
    public $empresa;

    public $item = [
        'produto' => null,
        'sujeitoDespachoId' => 1,
    ];
    public $formasPagamentos = [];

    public $fatura = [
        'dataEntradaEstacionamento' => null,
        'dataSaidaEstacionamento' => null,
        'moeda' => null,
        'tipoDocumento' => 3, //Fatura proforma
        'formaPagamentoId' => null, //Fatura proforma
        'moedaPagamento' => 'AOA',
        'observacao' => null,
        'isencaoIVA' => false,
        'isencaoOcupacao' => false,
        'unidadeMetrica' => null,
        'qtdMeses' => null,
        'addArCondicionado' => false,
        'tarifaConsumo' => true,
        'totalDesconto' => 0,
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
        'taxaImpostoPredial' => 0,
        'valorImpostoPredial' => 0,
        'total' => 0,
        'items' => []
    ];
    public $servicos;
    public $paises;
    public $tiposDocumentos;
    public $especificaoMercadorias;

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
        $this->fatura['taxaImpostoPredial'] = 0;
        $this->fatura['valorImpostoPredial'] = 0;
        $this->fatura['moeda'] = null;
        $this->fatura['total'] = 0;
        $this->fatura['items'] = [];
    }

    public function updatedFaturaAddArCondicionado($addArCondicionado)
    {
//        $this->resetField();
//        $this->fatura['addArCondicionado'] = $addArCondicionado;
//        if ($addArCondicionado) {
//            $this->fatura['isencaoOcupacao'] = false;
//        }
    }

//    public function updatedFaturaIsencaoOcupacao($isencaoOcupacao)
//    {
//        $this->resetField();
//        $this->fatura['isencaoOcupacao'] = $isencaoOcupacao;
//        if ($isencaoOcupacao) {
//            $this->fatura['addArCondicionado'] = false;
//        }
//
//
//    }

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
        $this->fatura['taxaImpostoPredial'] = 0;
        $this->fatura['valorImpostoPredial'] = 0;
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
        $simuladorFaturaCarga = new SimuladorFaturaServicoComercial(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaCarga->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);
    }

    public function updatedFaturaFormaPagamentoId($formaPagamentoId)
    {
        $this->fatura['formaPagamentoId'] = $formaPagamentoId;
        $simuladorFaturaCarga = new SimuladorFaturaServicoComercial(new DatabaseRepositoryFactory());
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

    public function mount()
    {
        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $this->fatura['moeda'] = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;
        $this->empresa = auth()->user()->empresa;

        $getBancos = new GetBancos(new DatabaseRepositoryFactory());
        $this->bancos = $getBancos->execute();

        $getProdutos = new GetProdutoPeloTipoServico(new DatabaseRepositoryFactory());
        $this->servicos = $getProdutos->execute(4);

        $getPaises = new GetPaises(new DatabaseRepositoryFactory());
        $this->paises = $getPaises->execute();

        $getTiposDocumentos = new GetTipoDocumentoByFaturacao(new DatabaseRepositoryFactory());
        $this->tiposDocumentos = $getTiposDocumentos->execute();
        $this->moedas = Moeda::get();
    }

    public function render()
    {
        $this->clientes = DB::table('clientes')->where('empresa_id', auth()->user()->empresa_id)->get();
        $this->especificaoMercadorias = DB::table('especificacao_mercadorias')->get();
        return view("empresa.facturacao.createAeroportoServicoComercial");
    }
    public function removeCart($item)
    {
        foreach ($this->fatura['items'] as $key => $itemCart) {
            $SERVICOS_ARCONDICIONADO = 39;
            $TARIFACONSUMO = 44;
            if ($TARIFACONSUMO) {
                $this->fatura['items'] = [];
            } else if (($this->fatura['isencaoOcupacao'] || $this->fatura['addArCondicionado']) && ($item['produtoId'] == $SERVICOS_ARCONDICIONADO)) {
                $this->fatura['items'] = [];
            } else if ($itemCart['produtoId'] == $item['produtoId']) {
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
        $SERVICO_PUBLICIDADE = 40;
        $SERVICOS_OCUPACAO = $produtoData->id >= 28 && $produtoData->id <= 38;
        $SERVICOS_OCUPACAO_DENTRO_TERMINAL = $produtoData->id == 41;

        if ($produtoData->id == $SERVICO_PUBLICIDADE || $SERVICOS_OCUPACAO) {
            $rules = [
                'fatura.qtdMeses' => 'required',
                'fatura.unidadeMetrica' => 'required',
            ];
            $messages = [
                'fatura.qtdMeses.required' => 'campo obrigatório',
                'fatura.unidadeMetrica.required' => 'campo obrigatório',
            ];
            $this->validate($rules, $messages);
        } else {
            if(!$SERVICOS_OCUPACAO_DENTRO_TERMINAL){
                $rules = [
                    'fatura.dataEntradaEstacionamento' => 'required',
                    'fatura.dataSaidaEstacionamento' => 'required',
                ];
                $messages = [
                    'fatura.dataEntradaEstacionamento.required' => 'campo obrigatório',
                    'fatura.dataSaidaEstacionamento.required' => 'campo obrigatório'
                ];
                $this->validate($rules, $messages);
            }

        }
//        $key = $this->isCart($produtoData);
//        if ($key !== false) {
//            $this->confirm('O serviço já foi adicionado', [
//                'showConfirmButton' => false,
//                'showCancelButton' => false,
//                'icon' => 'warning'
//            ]);
//            return;
//        }


        $produto = json_decode($this->item['produto']);
        $this->item['nomeProduto'] = $produto->designacao;
        $this->item['taxaIva'] = $produto->tipo_taxa->taxa;
        $this->item['impostoPredial'] = $produto->impostoPredial;
        $this->item['produtoId'] = $produto->id;
        $this->item['produto'] = $this->item['produto'];
        $this->item['taxa'] = $produto->preco_venda;
        $this->item['unidadeMetrica'] = $this->fatura['unidadeMetrica'];
        $this->item['addArCondicionado'] = $this->fatura['addArCondicionado'];
        $this->item['dataEntradaEstacionamento'] = $this->fatura['dataEntradaEstacionamento'];
        $this->item['dataSaidaEstacionamento'] = $this->fatura['dataSaidaEstacionamento'];
        $this->item['qtdMeses'] = $this->fatura['qtdMeses'];

        $SERVICOS_GABINETE = $produtoData->id == 30 || $produtoData->id == 32 || $produtoData->id == 34 || $produtoData->id == 37;
        $SERVICO_HANGARES_ESPACO_ABERTO = 33;
        $SERVICOS_CONSUMO = 44;
        $servidoConsumo = DB::table('produtos')
            ->join('tipotaxa', 'tipotaxa.codigo', 'produtos.codigo_taxa')
            ->where('id', $SERVICOS_CONSUMO)->first();



        if ($this->isExistServico($servidoConsumo->id) === false && $SERVICOS_GABINETE) {
            $this->addTarifaConsumo($servidoConsumo);
        }
        if (($this->fatura['isencaoOcupacao'] || $this->fatura['addArCondicionado']) && $SERVICOS_OCUPACAO) {
            $SERVICOS_ARCONDICIONADO = 39;
            $servidoArcondicionado = DB::table('produtos')
                ->join('tipotaxa', 'tipotaxa.codigo', 'produtos.codigo_taxa')
                ->where('id', $SERVICOS_ARCONDICIONADO)->first();
            $this->fatura['items'][] = (array)$this->item;
            $item['produto'] = json_encode($servidoArcondicionado);
            $item['nomeProduto'] = $servidoArcondicionado->designacao;
            $item['taxaIva'] = $servidoArcondicionado->taxa;
            $item['impostoPredial'] = $servidoArcondicionado->impostoPredial;
            $item['produtoId'] = $servidoArcondicionado->id;
            $item['taxa'] = 0;
            $item['unidadeMetrica'] = 0;
            $item['addArCondicionado'] = true;
            $item['qtdMeses'] = null;
            if ($this->isExistServico($item['produtoId']) === false) {
                $this->fatura['items'][] = (array)$item;
                if($produtoData->id == $SERVICO_HANGARES_ESPACO_ABERTO){
                    $this->addTarifaConsumo($servidoConsumo);
                }
            }
        } else {
            $this->fatura['items'][] = (array)$this->item;
        }
        $this->fatura['items'] = $this->array_sort($this->fatura['items'], 'produtoId', SORT_ASC);
        $this->resetQtdMesesAndUnidades();
        $this->calculadoraTotal();
    }
    public function addTarifaConsumo($servidoConsumo){
        $item['produto'] = json_encode($servidoConsumo);
        $item['nomeProduto'] = $servidoConsumo->designacao;
        $item['taxaIva'] = $servidoConsumo->taxa;
        $item['impostoPredial'] = $servidoConsumo->impostoPredial;
        $item['produtoId'] = $servidoConsumo->id;
        $item['taxa'] = 0;
        $item['unidadeMetrica'] = 0;
        $item['addArCondicionado'] = false;
        $item['qtdMeses'] = null;
        $this->fatura['items'][] = (array)$item;

    }

    public function resetQtdMesesAndUnidades()
    {
        $this->fatura['qtdMeses'] = null;
        $this->fatura['unidadeMetrica'] = null;
    }

    public function isExistServico($produtoId)
    {
        $items = collect($this->fatura['items']);
        $posicao = $items->search(function ($produto) use ($produtoId) {
            return $produto['produtoId'] === $produtoId;
        });
        return $posicao;
    }

    function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }

    public function calculadoraTotal()
    {
        $simuladorFaturaServicoComercial = new SimuladorFaturaServicoComercial(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaServicoComercial->execute($this->fatura);
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

    private function conversorModelParaArray(FaturaServicoComercial $output)
    {


        $fatura = [
            'dataEntradaEstacionamento' => $output->getDataEntradaEstacionamento(),
            'dataSaidaEstacionamento' => $output->getDataSaidaEstacionamento(),
            'moeda' => $output->getMoeda(),
            'unidadeMetrica' => $output->getUnidadeMetrica(),
            'qtdMeses' => $output->getQtdMeses(),
            'addArCondicionado' => $output->getAddArCondicionado(),
            'totalDesconto' => $output->getDesconto(),
            'tipoDocumento' => $output->getTipoDocumento(),
            'formaPagamentoId' => $output->getFormaPagamentoId(),
            'moedaPagamento' => $output->getMoedaPagamento(),
            'observacao' => $output->getObservacao(),
            'isencaoIVA' => $output->getIsencaoIVA(),
            'isencaoOcupacao' => $output->getIsencaoOcupacao(),
            'tarifaConsumo' => $output->getTarifaConsumo(),
            'retencao' => $output->getRetencao(),
            'taxaRetencao' => $output->getTaxaRetencao(),
            'valorRetencao' => $output->getValorRetencao(),
            'nomeProprietario' => $output->getProprietario(),
            'clienteId' => $output->getClienteId(),
            'nomeCliente' => $output->getNomeCliente(),
            'telefoneCliente' => $output->getTelefoneCliente(),
            'nifCliente' => $output->getNifCliente(),
            'emailCliente' => $output->getEmailCliente(),
            'enderecoCliente' => $output->getEnderecoCliente(),

            'taxaIva' => $output->getTaxaIva(),
            'cambioDia' => $output->getCambioDia(),
            'valorConsumo' => $output->getTotalTarifaConsumo(),
            'contraValor' => $output->getContraValor(),
            'valorliquido' => $output->getValorLiquido(),
            'valorDesconto' => $output->getDesconto(),
            'valorIliquido' => $output->getValorIliquido(),
            'valorImposto' => $output->getValorImposto(),
            'taxaImpostoPredial' => $output->getTaxaImpostoPredial(),
            'valorImpostoPredial' => $output->getValorImpostoPredial(),
            'total' => $output->getTotal(),
            'items' => []
        ];
        foreach ($output->getItems() as $key => $item) {
            array_push($fatura['items'], [
                'produtoId' => $item->getProdutoId(),
                'quantidade' => 1,
                'qtdMeses' => $item->getQtdMeses(),
                'nomeProduto' => $item->getNomeProduto(),
                'valorIva' => $item->getValorIva(),
                'taxaIva' => $item->getTaxaIva(),
                'impostoPredial'=> $item->getTaxaImpostoPredial(),
                'valorImpostoPredial'=> $item->getValorImpostoPredial(),
                'considera1hDepois30min' => $item->getConsidera1hDepois30min(),
                'taxa' => $item->getTaxa(),
                'unidadeMetrica' => $item->getUnidadeMetrica(),
                'addArCondicionado' => $item->getAddArCondicionado(),
                'desconto' => $item->getValorDesc(),
                'cambioDia' => $item->getCambioDia(),
                'total' => $item->getTotal(),
                'valorConsumo' => $item->getTotal(),
                'totalIva' => $item->getValorTotalComImpostos(),
                'descHoraEstacionamento' => $item->getDescHoraEstacionamento(),
                'dataEntradaEstacionamento' => $item->getDataEntradaEstacionamento(),
                'dataSaidaEstacionamento' => $item->getDataSaidaEstacionamento(),
            ]);
        }
        return $fatura;
    }

    public function emitirDocumento()
    {

        $rules = [
            'fatura.clienteId' => 'required',
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

        $emitirDocumento = new EmitirDocumentoAeroportoServicoComercial(new DatabaseRepositoryFactory());
        $faturaId = $emitirDocumento->execute(new Request($this->fatura));
        $this->printFaturaComercial($faturaId);
        $this->resetField();
    }

    public function printFaturaComercial($facturaId)
    {
        $factura = DB::table('facturas')->where('id', $facturaId)->first();

        $filename = "WinmarketServicoComercial";
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
        unlink($report['filename']);
        flush();
    }

    public function resetField()
    {
        $this->fatura = [
            'dataEntradaEstacionamento' => null,
            'dataSaidaEstacionamento' => null,
            'moeda' => null,
            'tipoDocumento' => 3, //Fatura proforma
            'formaPagamentoId' => null, //Fatura proforma
            'moedaPagamento' => 'AOA',
            'observacao' => null,
            'unidadeMetrica' => null,
            'qtdMeses' => null,
            'addArCondicionado' => false,
            'totalDesconto' => 0,
            'isencaoIVA' => false,
            'isencaoOcupacao' => false,
            'tarifaConsumo' => true,
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
            'tipoDeAeronave' => null,
            'pesoMaximoDescolagem' => null,
            'dataDeAterragem' => null,
            'dataDeDescolagem' => null,
            'horaDeAterragem' => null, //11h40 UTC
            'horaDeDescolagem' => null, //13h57 UTC
            'peso' => null,
            'horaExtra' => null,
            'taxaIva' => 0,
            'cambioDia' => 0,
            'contraValor' => 0,
            'valorliquido' => 0,
            'valorDesconto' => 0,
            'valorIliquido' => 0,
            'valorImposto' => 0,
            'taxaImpostoPredial' => 0,
            'valorImpostoPredial' => 0,
            'total' => 0,
            'items' => []
        ];
        $this->formasPagamentos = [];
    }
}
