<?php

namespace App\Http\Controllers\empresa\Faturacao;

use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientePeloNumero;
use App\Application\UseCase\Empresa\CartaoCliente\IsValidoCartaoCliente;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumento;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturacao;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamento;
use App\Application\UseCase\Empresa\Parametros\GetFormatoImpressaoDocumentoFaturacao;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabel;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPorArmazem;
use App\Domain\Entity\Empresa\Faturacao\Fatura;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use App\Infra\Repository\Empresa\Relatorios\TraitRelatorioFaturacaoJasper;
use App\Infra\Traits\TraitRuleUnique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;


class FaturacaoCreateController extends Component
{
    use LivewireAlert;
    use TraitRelatorioFaturacaoJasper;

    public $itemCarrinhoRemover;
    public $produtoSelecionado;
    public $quantidadeStock = 0;
    public $cartaoNomeCliente;
    public $produtoDiverso;
    public $checkIvaProdutoDiverso = false;
    public $armazens = [];
    public $clientes = [];
    public $formasPagamento = [];
    public $showPagamento = true;
    public $showDescontoRetencao = false;
    public $temCartaoCliente = false;
    public $selectedMulticaixa = null;
    public $selectedVendaCredito = null;
    public $quantidade = 1;
    public $produto;
    public $produtoSelecionadoId = null;
    public $produtos = [];

    public $produtoItemModal;

    public $fatura = [];
    public $searchProduto = null;
    public $searchcliente = null;

    protected $listeners = ['selectedItem', 'removerItemCarrinho', 'cancelarPagarComSaldoCliente', 'limparTodoCarrinho', 'refreshComponent' => '$refresh'];

    public function __construct($id = null)
    {
        if (!Auth::check()) return $this->redirect('/');
    }

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        if($item['atributo'] == 'armazemId'){
            $getProdutos = new GetProdutosPorArmazem(new DatabaseRepositoryFactory());
            $this->produtos = $getProdutos->execute($this->searchProduto, $item['valor']);
        }
        $this->fatura[$item['atributo']] = $item['valor'];
        if ($item['atributo'] == 'clienteId') {
            $cliente = $this->buscarCliente($this->fatura['clienteId']);
            $this->setarValorDoCliente($cliente);
        } else {
            if ($item['atributo'] == 'formaPagamentoId' && $item['valor'] == 3) {
                $this->selectedMulticaixa = true;
                $this->selectedVendaCredito = false;
                $this->fatura['totalEntregue'] = 0;
            } else if ($item['atributo'] == 'formaPagamentoId' && $item['valor'] == 2) {
                $this->selectedVendaCredito = true;
                $this->selectedMulticaixa = false;
                $this->fatura['totalEntregue'] = 0;
                $this->fatura['totalMulticaixa'] = 0;
                $this->fatura['tipoDocumento'] = 2;
            }else if($item['atributo'] == 'produtoSelecionado'){
                $prod = (array) json_decode($item['valor']);
                if(count($prod) > 0){
                    $this->produtoSelecionadoId = $prod['produtoId'];
                    $this->quantidadeStock = $prod['quantidadeStock'];
                    $this->produto = (array) json_decode($item['valor']);
//                $produto = (array) json_decode($item['valor']);
//                $this->adicionarCarrinho($produto, 1);
                }

            }
            else{
                $this->selectedVendaCredito = false;
                $this->selectedMulticaixa = false;
                $this->fatura['totalEntregue'] = 0;
                $this->fatura['totalMulticaixa'] = 0;
            }

            try {
                $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
                $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
                $this->fatura = $this->conversorModelParaArray($fatura);
            } catch (\Error $error) {
                $this->confirm($error->getMessage(), [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }


        }
    }

    public function selecionarTipoDocumento($tipoDocumento)
    {

        $this->fatura['items'] = [];

        if ($tipoDocumento == 1) {
            $this->selectedVendaCredito = false;
            $this->selectedMulticaixa = false;
            $this->fatura['formaPagamentoId'] = 1;
            $this->fatura['totalEntregue'] = null;
            $this->fatura['totalMulticaixa'] = null;
        } else if ($tipoDocumento == 2) {
            $this->selectedVendaCredito = true;
            $this->selectedMulticaixa = false;
            $this->fatura['totalEntregue'] = null;
            $this->fatura['formaPagamentoId'] = 2;

            $this->fatura['totalMulticaixa'] = null;
        } else {
            $this->selectedVendaCredito = true;
            $this->selectedMulticaixa = false;
            $this->fatura['totalEntregue'] = null;
            $this->fatura['formaPagamentoId'] = null;
            $this->fatura['totalMulticaixa'] = null;
        }

        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
    }
    public function updatedSearchProduto(){
        $getProdutos = new GetProdutosPorArmazem(new DatabaseRepositoryFactory());
        $this->produtos = $getProdutos->execute($this->searchProduto, $this->fatura['armazemId']);
    }

    public function mount()
    {

        $this->fatura = $this->setarDadoFatura();
        $getArmazens = new GetArmazens(new DatabaseRepositoryFactory());
        $this->armazens = $getArmazens->execute();
        $this->fatura['armazemId'] = $this->armazens[0]['id'];
        $this->selectedVendaCredito = false;
        $this->selectedMulticaixa = false;
        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $this->clientes = $getClientes->execute($this->searchcliente);
        $this->fatura['clienteId'] = $this->clientes[0]['id'];
        $this->fatura['clienteDiverso'] = $this->clientes[0]['diversos'];
        $this->fatura['nomeCliente'] = $this->clientes[0]['nome'];
        $this->fatura['nifCliente'] = $this->clientes[0]['nif'];
        $this->fatura['emailCliente'] = $this->clientes[0]['email'];
        $this->fatura['telefoneCliente'] = $this->clientes[0]['telefone_cliente'];
        $this->fatura['enderecoCliente'] = $this->clientes[0]['endereco'];
        $this->fatura['contaCorrenteCliente'] = $this->clientes[0]['conta_corrente'];

        $getFormasPagamento = new GetFormasPagamento(new DatabaseRepositoryFactory());
        $this->formasPagamento = $getFormasPagamento->execute();
        $this->fatura['formaPagamentoId'] = $this->formasPagamento[0]['id'];
        $this->fatura['tipoDocumento'] = 1; //Fatura recibo
        $getProdutos = new GetProdutosPorArmazem(new DatabaseRepositoryFactory());
        $this->produtos = $getProdutos->execute($this->searchProduto, $this->fatura['armazemId']);
    }


    public function setarValorDoCliente($cliente)
    {
        $this->fatura['clienteId'] = $cliente['id'];
        $this->fatura['clienteDiverso'] = $cliente['diversos'];
        $this->fatura['nomeCliente'] = $cliente['nome'];
        $this->fatura['nifCliente'] = $cliente['nif'];
        $this->fatura['emailCliente'] = $cliente['email'];
        $this->fatura['telefoneCliente'] = $cliente['telefone_cliente'];
        $this->fatura['enderecoCliente'] = $cliente['endereco'];
        $this->fatura['contaCorrenteCliente'] = $cliente['conta_corrente'];
    }

    public function setarDadoFatura()
    {
        return [
            'clienteId' => null,
            'numeroCartaoCliente' => null,
            'nomeCliente' => null,
            'clienteDiverso' => 'Sim',
            'nifCliente' => null,
            'emailCliente' => null,
            'telefoneCliente' => null,
            'contaCorrenteCliente' => null,
            'valorDescontarSaldo' => null,
            'aplicadoCartaoCliente' => false,
            'saldoCliente' => 0,
            'formaPagamentoId' => 1,
            'armazemId' => 1,
            'totalPagar' => 0,
            'totalIva' => 0,
            'totalTroco' => 0,
            'totalDesconto' => 0,
            'totalPrecoFactura' => 0,
            'totalIncidencia' => 0,
            'totalRetencao' => 0,
            'desconto' => 0,
            'totalExtenso' => null,
            'isRetencao' => false,
            'tipoDocumento' => 1,
            'tipoFolha' => "A4",
            'totalEntregue' => null,
            'totalMulticaixa' => null,
            'totalCash' => null,
            'observacao' => null,
            'items' => []
        ];
    }

    public function buscarCliente($clienteId)
    {
        $elementoEncontrado = null;
        foreach ($this->clientes as $cliente) {
            if ($cliente['id'] == $clienteId) {
                $elementoEncontrado = $cliente;
                break;
            }
        }
        return $elementoEncontrado;
    }

    public function render()
    {

        $getLayouVenda = new GetParametroPeloLabel(new DatabaseRepositoryFactory());
        $layoutVendaOnline = $getLayouVenda->execute();

        if (!$layoutVendaOnline) {
            return view('empresa.facturacao.createNovo')->layout('layouts.appFaturacao');

        } else if ($layoutVendaOnline['valor'] == 'vendas Online') {
            return view('empresa.facturacao.createVendaOnline')->layout('layouts.appFaturacao');
        }
        return view('empresa.facturacao.createNovo')->layout('layouts.appFaturacao');
    }

    public function showModalLimparTodoCarrinho()
    {
        $this->confirm('Deseja limpar o carrinho?', [
            'onConfirmed' => 'limparTodoCarrinho',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }

    public function limparTodoCarrinho()
    {
        $this->fatura['items'] = [];
        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

    }

    public function showModalRemoverItem($item)
    {
        $this->itemCarrinhoRemover = $item;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'removerItemCarrinho',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }

    public function removerItemCarrinho()
    {
        $posicao = $this->isCart($this->itemCarrinhoRemover);
        unset($this->fatura['items'][$posicao]);
        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

    }

    public function showModalItemProduto($item)
    {


        $posicaoArmazem = $this->getArmazem($item['armazemId']);
        $posicao = $this->isCart($item);
        $this->produtoItemModal['key'] = $posicao;
        $this->produtoItemModal['produtoId'] = $item['produtoId'];
        $this->produtoItemModal['nomeProduto'] = Str::upper($item['nomeProduto']);
        $this->produtoItemModal['quantidade'] = $item['quantidade'];
        $this->produtoItemModal['desconto'] = $item['desconto'];
        $this->produtoItemModal['armazemNome'] = $this->armazens[$posicaoArmazem]['designacao'];
    }


    public function updatedFaturaValorDescontarSaldo($valorDescontarSaldo)
    {
        if ($valorDescontarSaldo > $this->fatura['saldoCliente']) {
            $this->fatura['valorDescontarSaldo'] = null;
            $this->confirm("Saldo insuficiente", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $this->fatura['valorDescontarSaldo'] = !is_numeric($this->fatura['valorDescontarSaldo']) ? null : ($this->fatura['valorDescontarSaldo'] ?? 0);

    }

    public function updatedProdutoItemModalDesconto($desconto)
    {
        $desconto = !is_numeric($desconto) ? 0 : $desconto ?? 0;
        if ($desconto > 100) {
            $this->produtoItemModal['desconto'] = 100;
        }
        if ($this->fatura['desconto'] >= 100) {
            $this->produtoItemModal['desconto'] = 0;
            $this->fatura['items'][$this->produtoItemModal['key']]['desconto'] = 0;
        }
        $this->fatura['items'][$this->produtoItemModal['key']]['desconto'] = $desconto;
        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);

//            dd($fatura);
//            dd($fatura->getItems()[0]->subTotalDesconto());
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
    }

    public function updatedProdutoItemModalQuantidade($quantidade)
    {
        try {
            $fatura = $this->fatura;
            if(!is_numeric($quantidade) || $quantidade == 0 || $quantidade == null){
                $fatura['items'][$this->produtoItemModal['key']]['quantidade'] = 1;
            }else {
                $fatura['items'][$this->produtoItemModal['key']]['quantidade'] = $quantidade;
            }
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $e) {
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

    }

    public function adicionarItemCarrinho()
    {

        if (!$this->produtoSelecionadoId) {
            $this->confirm('Seleciona um produto', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        if ($this->quantidade <= 0 || !$this->quantidade) {
            $this->confirm('Informe a quantidade descontar no stock', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $produto = $this->produto;
        $key = $this->isCart($produto);
        if ($key !== false) {
            $existenciaStock = new ExistenciaStockRepository();
//            $qty = $this->fatura['items'][$key]['quantidade'];
            $produto['quantidade'] = $this->quantidade;
            $produto = (object)$produto;

            $isStock = $existenciaStock->isStock($produto, $produto->armazemId);
            if (!$isStock && $produto->isEstocavel == 'Sim' && $this->fatura['tipoDocumento'] != 3) {
                $this->confirm('Quantidade indisponível no estoque', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
            $this->fatura['items'][$key]['quantidade'] = $this->quantidade;
        } else {

            $produto['quantidade'] = $this->quantidade;
            $produto['desconto'] = 0;
            $produto = (object)$produto;
            $existenciaStock = new ExistenciaStockRepository();
            $isStock = $existenciaStock->isStock($produto, $produto->armazemId);
            if (!$isStock && $produto->isEstocavel == 'Sim' && $this->fatura['tipoDocumento'] != 3) {
                $this->confirm('Quantidade indisponível no estoque', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
            $this->fatura['items'][] = (array)$produto;
        }

        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $e) {
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }
    }

    public function getTaxaIva()
    {
        $getValorIva = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $parametroIva = $getValorIva->execute('valor_iva_aplicado');
        return (double)$parametroIva->valor;
    }

    public function getValorIva($precoUnit, $taxaIva)
    {
        return $precoUnit * $taxaIva / 100;
    }

    public function getPvp($precoUnit, $valorIva)
    {
        return $precoUnit + $valorIva;
    }
    public function updatedCheckIvaProdutoDiverso(){
        $taxaIva = 0;
        if ($this->checkIvaProdutoDiverso) {
            $taxaIva = $this->getTaxaIva();
        }
        $precoVenda = $this->produtoDiverso['precoUnitSemIva']??0;
        $valorIva = $this->getValorIva($precoVenda, $taxaIva);
        $this->produtoDiverso['precoUnitComIva'] = $this->getPvp($precoVenda, $valorIva);
    }

    public function updatedProdutoDiversoPrecoUnitSemIva($precoVenda = 0)
    {
        $taxaIva = 0;
        if ($this->checkIvaProdutoDiverso) {
            $taxaIva = $this->getTaxaIva();
        }
        $valorIva = $this->getValorIva($precoVenda, $taxaIva);
        $this->produtoDiverso['precoUnitComIva'] = $this->getPvp($precoVenda, $valorIva);

    }

    public function addProdutoDiversos()
    {
        $taxaIva = 0;
        if ($this->checkIvaProdutoDiverso) {
            $taxaIva = $this->getTaxaIva();
        }
        $valorIva = $this->getValorIva($this->produtoDiverso['precoUnitSemIva'], $taxaIva);
        $pvp = $this->getPvp($this->produtoDiverso['precoUnitSemIva'], $valorIva);

        $data = [
            "produtoId" => 1,
            "armazemId" => $this->fatura['armazemId'],
            "codigoProduto" => "DIVERSO01",
            "codigoBarraProduto" => "DIVERSO01",
            "nomeProduto" => $this->produtoDiverso['descricaoProduto'],
            "precoVendaProduto" => $this->produtoDiverso['precoUnitSemIva'],
            "pvp" => $pvp,
            "iva" => $valorIva,
            "produtoCartaGarantia" => "N",
            "tempoGarantiaProduto" => null,
            "tipoGarantia" => null,
            "precoCompraProduto" => 0,
            "quantidadeStock" => 0,
            "isEstocavel" => "Nao",
            "quantidadeMinima" => 0,
            "quantidadeCritica" => 0,
            "taxaIva" => $taxaIva
        ];
        $this->adicionarCarrinho($data, 1);


//        "produtoId" => 664
//  "armazemId" => 110
//  "codigoProduto" => "1200DPI"
//  "codigoBarraProduto" => "6952655956734"
//  "nomeProduto" => "MOUSE COM FIO HP"
//  "precoVendaProduto" => 6000
//  "pvp" => 5700
//  "iva" => 840
//  "produtoCartaGarantia" => "N"
//  "tempoGarantiaProduto" => null
//  "tipoGarantia" => null
//  "precoCompraProduto" => 4000
//  "quantidadeStock" => 0
//  "isEstocavel" => "Sim"
//  "quantidadeMinima" => 30
//  "quantidadeCritica" => 5
//  "taxaIva" => 14

//        dd($this->produtoDiverso);
//
//        $produto['produtoId'] = '';
//        dd($this->fatura['armazemId']);
    }

    public function adicionarCarrinho($produto, $key)
    {

        $key = $this->isCart($produto);

        if ($key !== false) {

            $existenciaStock = new ExistenciaStockRepository();
            $qty = $this->fatura['items'][$key]['quantidade'];
            $produto['quantidade'] = ++$qty;
            $produto = (object)$produto;


            $isStock = $existenciaStock->isStock($produto, $produto->armazemId);
            if (!$isStock && $produto->isEstocavel == 'Sim' && $this->fatura['tipoDocumento'] != 3) {
                $this->confirm('Quantidade indisponível no estoque', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
            $this->fatura['items'][$key]['quantidade']++;
        } else {


            $produto['quantidade'] = 1;
            $produto['desconto'] = 0;
            $produto = (object)$produto;
            $existenciaStock = new ExistenciaStockRepository();
            $isStock = $existenciaStock->isStock($produto, $produto->armazemId);

            if (!$isStock && $produto->isEstocavel == 'Sim' && $this->fatura['tipoDocumento'] != 3) {
                $this->confirm('Quantidade indisponível no estoque', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }

            $this->fatura['items'][] = (array)$produto;

        }

        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $e) {
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }

    }


    public function updatedFaturaTotalEntregue()
    {
        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

    }

    public function updatedFaturaTotalMulticaixa()
    {
        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }


    }

    public function updatedFaturaIsRetencao()
    {

        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
        } catch (\Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }

    }

    public function showModalCartaoCliente()
    {
//        $this->cartaoNomeCliente = null;
//        $this->fatura['saldoCliente'] = 0;
//        $this->fatura['saldoClienteAux'] = 0;
//        $this->fatura['valorDescontarSaldo'] = null;
//        $this->fatura['numeroCartaoCliente'] = null;
    }

    public function updatedFaturaNumeroCartaoCliente($numeroCartaoCliente)
    {
        $getCartaoCliente = new GetCartaoClientePeloNumero(new DatabaseRepositoryFactory());
        $cartaoCliente = $getCartaoCliente->execute($numeroCartaoCliente);

        if ($cartaoCliente) {
            $this->setarValorDoCliente($cartaoCliente->cliente);
            $this->cartaoNomeCliente = Str::upper($cartaoCliente->cliente['nome']);
            $this->fatura['saldoCliente'] = $cartaoCliente->saldo ?? 0;
            $this->fatura['saldoClienteAux'] = number_format($cartaoCliente->saldo ?? 0, 2, ',', '.');
            $this->temCartaoCliente = true;
            $this->fatura['valorDescontarSaldo'] = 0;
//            $this->fatura['numeroCartaoCliente'] = $cartaoCliente['numeroCartao'];
        } else {
            $this->fatura['saldoCliente'] = number_format(0, 2, ',', '.');
            $this->cartaoNomeCliente = null;
            $this->fatura['saldoClienteAux'] = 0;
            $this->fatura['valorDescontarSaldo'] = 0;
            $this->temCartaoCliente = false;


        }
    }

    public function aplicarCartaoCliente()
    {

        if (!$this->temCartaoCliente) {
            $this->confirm("Cartão cliente não encontrado", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        if (!$this->fatura['numeroCartaoCliente']) {
            $this->confirm("Informe o número do cartão cliente", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        if (count($this->fatura['items']) <= 0) {
            $this->confirm("Carrinho está vazio", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $getCartaoCliente = new IsValidoCartaoCliente(new DatabaseRepositoryFactory());
        $isValidoCartaoCliente = $getCartaoCliente->execute($this->fatura['numeroCartaoCliente']);
        if (!$isValidoCartaoCliente) {
            $this->confirm("Cartão cliente expirado", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }


        $valorDescontarSaldo = !is_numeric($this->fatura['valorDescontarSaldo']) ? 0 : $this->fatura['valorDescontarSaldo'] ?? 0;
//        if (($this->fatura['saldoCliente'] < $valorDescontarSaldo) || $valorDescontarSaldo <= 0) {
//            $this->confirm("Saldo insuficiente", [
//                'showConfirmButton' => false,
//                'showCancelButton' => false,
//                'icon' => 'warning'
//            ]);
//            return;
//        }

        $this->fatura['aplicadoCartaoCliente'] = true;
        $this->fatura['formaPagamentoId'] = 1;
        $this->fatura['tipoDocumento'] = 1;
        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
            $this->confirm("Saldo do cartão aplicado", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        } catch (\Error  $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }

    }

    public function updatedFaturaDesconto()
    {
        $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
        $this->fatura = $this->conversorModelParaArray($fatura);
    }

    public function showCancelarPagarComSaldoCliente()
    {
        $this->confirm('Deseja cancelar o pagamento com saldo?', [
            'onConfirmed' => 'cancelarPagarComSaldoCliente',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }

    public function cancelarPagarComSaldoCliente()
    {
        $this->fatura['aplicadoCartaoCliente'] = false;
        $this->fatura['saldoCliente'] = number_format(0, 2, ',', '.');
        $this->cartaoNomeCliente = null;
        $this->fatura['saldoClienteAux'] = 0;
        $this->fatura['valorDescontarSaldo'] = 0;
        $this->fatura['numeroCartaoCliente'] = null;

        try {
            $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $fatura = $simuladorFaturacao->execute(new Request($this->fatura));
            $this->fatura = $this->conversorModelParaArray($fatura);
            $this->confirm("Pagamento com saldo cancelado", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        } catch (Error $error) {
            $this->confirm($error->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }
    }

    private function isCart($item)
    {
        $items = collect($this->fatura['items']);
        $posicao = $items->search(function ($produto) use ($item) {
            return $produto['produtoId'] === $item['produtoId'];
        });
        return $posicao;
    }
    private function getArmazem($armazemId)
    {
        $items = $this->armazens;
        $posicao = $items->search(function ($armazem) use ($armazemId) {
            return $armazem['id'] === $armazemId;
        });
        return $posicao;
    }
    private function conversorModelParaArray(Fatura $output)
    {
        $fatura = [
            'clienteId' => $output->getClienteId(),
            'nomeCliente' => $output->getNomeCliente(),
            'nifCliente' => $output->getNifCliente(),
            'clienteDiverso' => $this->fatura['clienteDiverso'],
            'emailCliente' => $output->getEmailCliente(),
            'numeroCartaoCliente' => $output->getNumeroCartaoCliente(),
            'valorDescontarSaldo' => $output->getValorDescontarSaldo() == 0 ? null : (float)$output->getValorDescontarSaldo(),
            'aplicadoCartaoCliente' => $output->getAplicadoCartaoCliente(),
            'saldoCliente' => (float)$output->getSaldoCliente(),
            'saldoClienteAux' => (float)$output->getSaldoCliente(),
            'telefoneCliente' => $output->getTelefoneCliente(),
            'enderecoCliente' => $output->getEnderecoCliente(),
            'contaCorrenteCliente' => $output->getContaCorrenteCliente(),
            'formaPagamentoId' => $output->getFormaPagamentoId(),
            'armazemId' => $output->getArmazemId(),
            'totalPagar' => (float)$output->getTotalPagar(),
            'totalIva' => (float)$output->getTotalIva(),
            'totalTroco' => (float)$output->getTotalTroco(),
            'totalDesconto' => (float)$output->getTotalDesconto(),
            'totalPrecoFactura' => (float)$output->getTotalPrecoFactura(),
            'totalRetencao' => (float)$output->getTotalRetencao(),
            'totalIncidencia' => (float)$output->getTotalIncidencia(),
            'desconto' => $output->getDesconto() == 0 ? null : (float)$output->getDesconto(),
            'totalExtenso' => $output->getTotalExtenso(),
            'isRetencao' => $output->getIsRetencao(),
            'tipoDocumento' => $output->getTipoDocumento(),
            'tipoFolha' => $output->getTipoFolha(),
            'totalEntregue' => $output->getTotalEntregue() == 0 ? null : (float)$output->getTotalEntregue(),
            'totalMulticaixa' => $output->getTotalMulticaixa() == 0 ? null : $output->getTotalMulticaixa(),
            'totalCash' => $output->getTotalCash() == 0 ? null : (float)$output->getTotalCash(),
            'observacao' => $output->getObservacao(),
            "items" => []
        ];

        foreach ($output->getItems() as $item) {
            array_push($fatura['items'], [
                'produtoId' => $item->getProdutoId(),
                'nomeProduto' => $item->getNomeProduto(),
                'codigoProduto' => $item->getCodigoProduto(),
                'produtoCartaGarantia' => $item->getProdutoCartaGarantia(),
                'tempoGarantiaProduto' => $item->getTempoGarantiaProduto(),
                'tipoGarantia' => $item->getTipoGarantia(),
                'armazemId' => $item->getArmazemId(),
                'precoVendaProduto' => $item->getPrecoVendaProduto(),
                'pvp' => $item->getPvp(),
                'precoCompraProduto' => $item->getPrecoVendaProduto(),
                'quantidadeStock' => $item->getQuantidadeStock(),
                'isEstocavel' => $item->getIsEstocavel(),
                'quantidadeMinima' => $item->getQuantidadeMinima(),
                'quantidadeCritica' => $item->getQuantidadeCritica(),
                'taxaIva' => $item->getTaxaIva(),
                'iva' => $item->getIva(),
                'quantidade' => $item->getQuantidade(),
                'desconto' => $item->getDesconto(),
                'subTotalDesconto' => $item->subTotalDesconto(),
                'subTotalTaxaIva' => $item->subTotalTaxaIva(),
                'subTotalPrecoProduto' => $item->subTotalPrecoProduto(),
                'subTotalIncidencia' => $item->subTotalIncidencia(),
            ]);
        }
        return $fatura;
    }

    public function showPagamento()
    {
        $this->showPagamento = !$this->showPagamento;
    }

    public function showDescontoRetencao()
    {
        $this->showDescontoRetencao = !$this->showDescontoRetencao;
    }

    public function imprimirRelatorioDiario()
    {
        try {
            $this->imprimirRelatorioDiariaOperadorLogado();
        } catch (\Error $ex) {
            Log::error($ex->getMessage());
            $this->confirm($ex->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
    }

    public function emitirDocumento()
    {
        if ($this->fatura['aplicadoCartaoCliente']) {
            $totalEntregue = $this->fatura['valorDescontarSaldo'] + (float)$this->fatura['totalEntregue'] + (float)$this->fatura['totalMulticaixa'];
            if ($totalEntregue < $this->fatura['totalPagar']) {
                $this->confirm("O valor a pagar é insuficiente", [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
        }
        if ($this->fatura['tipoDocumento'] == 2 && $this->fatura['clienteDiverso'] == 'Sim') {
            $this->confirm("Cliente diversos não emite fatura", [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }


        try {
            $emitirDocumento = new EmitirDocumento(new DatabaseRepositoryFactory());
            $outputFatura = $emitirDocumento->execute(new Request($this->fatura));
//            $formato = new GetFormatoImpressaoDocumentoFaturacao(new DatabaseRepositoryFactory());
//            $formato = $formato->execute();

            $getParametroImpressao = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
            $parametroImpressao = $getParametroImpressao->execute('tipoImpreensao');


            if ($parametroImpressao->valor == 'ticket') {
                $this->imprimirDocumentoFormatoTicket($outputFatura->id);
            } else {
                $filename = $parametroImpressao->valor == 'A4'?'Winmarket':'Winmarket_A5';
                $this->imprimirDocumentoFaturacao($outputFatura, $filename);
            }


//            if($this->fatura['tipoFolha'] !== "A4"){
//                $this->imprimirDocumentoFormatoTicket($outputFatura->id);
//            }else if ($formato->valor == "A4" && $this->fatura['tipoFolha'] !== "A4") {
//                $this->imprimirDocumentoFaturacao($outputFatura);
//            }else if($formato->valor == "A4" && $this->fatura['tipoFolha'] == "A4"){
//                $this->imprimirDocumentoFaturacao($outputFatura);
//            }
//            else {
//                $this->imprimirDocumentoFormatoTicket($outputFatura->id);
//            }
            $this->mount();
        } catch (\Error $e) {
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
        }
    }

}
