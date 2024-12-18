<?php

namespace App\Http\Controllers\empresa\Pdv;

use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturacao;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamento;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPorArmazem;
use App\Domain\Entity\Empresa\Faturacao\Fatura;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PdvCreateController extends Component
{
    use LivewireAlert;

    public $search = null;
    public $produtos = [];
    public $armazens = [];
    public $clientes = [];
    public $formasPagamento = [];
    public $items = [];
    public $fatura = [
        "totalPagar" => 0,
        "totalIva" => 0,
        "totalDesconto" => 0,
        "totalPrecoFatura" => 0,
        "items" => []
    ];


    public $valorEntregue = 0;
    public $formaPagamentoId = 1;
    public $armazemId;
    public $clienteId = 1;
    public $nomeCliente = "Consumidor final";
    public $nifCliente = "999999999";
    public $enderecoCliente = null;
    public $emailCliente = null;
    public $desconto = 0;
    public $isRetencao = false;

    public function mount()
    {
        $getArmazens = new GetArmazens(new DatabaseRepositoryFactory());
        $this->armazens = $getArmazens->execute();
        $getFormasPagamento = new GetFormasPagamento(new DatabaseRepositoryFactory());
        $this->formasPagamento = $getFormasPagamento->execute();

        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $this->clientes = $getClientes->execute();

        $this->clienteId = $this->clientes[0]['id'];
        $this->armazemId = $this->armazens[0]['id'];
        $this->armazemId = $this->armazens[0]['id'];
        $this->formaPagamentoId = $this->formasPagamento[0]['id'];
    }

    public function render()
    {
        $getProdutos = new GetProdutosPorArmazem(new DatabaseRepositoryFactory());
        $this->produtos = $getProdutos->execute($this->search, $this->armazemId);
        return view('empresa.pdvNew.create')->extends('layouts.appPdv');
    }

    public function updatedDesconto($desconto)
    {
        $this->desconto = $this->desconto ? ($this->desconto > 100 ? 100 : $this->desconto) : null;
        $input = $this->inputFatura();
        $this->simuladorFatura($input);
    }

    public function removerItemCarrinho($item, $key)
    {
        array_splice($this->items, $key, 1);
        $input = $this->inputFatura();
        $this->simuladorFatura($input);
    }

    private function inputFatura()
    {
        return [
            "valorEntregue" => $this->valorEntregue,
            "formaPagamentoId" => $this->formaPagamentoId,
            "armazemId" => $this->armazemId,
            "clienteId" => $this->clienteId,
            "nomeCliente" => "Consumidor final",
            "nifCliente" => "999999999",
            "enderecoCliente" => null,
            "emailCliente" => null,
            "desconto" => $this->desconto??0,
            "isRetencao" => false,
            "items" => $this->items
        ];
    }

    public function adicionarCarrinho($item, $key)
    {
        $isCart = $this->isCart($item);
        if ($isCart) {
            $this->items[$key]['quantidade']++;
        } else {
            $item['quantidade'] = 1;
            $this->items[$key] = $item;
        }
        $input = $this->inputFatura();
        $this->simuladorFatura($input);
    }

    private function simuladorFatura($input)
    {
        try {
            $simularFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
            $output = $simularFaturacao->execute(new Request($input));
            $this->fatura = $this->conversorModelParaArray($output);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->alert('warning', $e->getMessage());
        }
    }

    private function conversorModelParaArray(Fatura $output)
    {
        $fatura = [
            "valorEntregue" => $output->getValorEntregue(),
            "formaPagamentoId" => $output->getFormaPagamentoId(),
            "armazemId" => $output->getArmazemId(),
            "clienteId" => $output->getClienteId(),
            "nomeCliente" => $output->getNomeCliente(),
            "nifCliente" => $output->getNifCliente(),
            "enderecoCliente" => $output->getEnderecoCliente(),
            "emailCliente" => $output->getEmailCliente(),
            "desconto" => $output->getDesconto(),
            "isRetencao" => $output->getIsRetencao(),
            "totalPagar" => $output->getTotalPagar(),
            "totalPrecoFatura" => $output->getTotalPrecoFatura(),
            "totalIva" => $output->getTotalIva(),
            "totalDesconto" => $output->getTotalDesconto(),
            "items" => []
        ];
        foreach ($output->getItems() as $item) {
            array_push($fatura['items'], [
                'produtoId' => $item->getProdutoId(),
                'nomeProduto' => $item->getNomeProduto(),
                'precoVendaProduto' => $item->getPrecoVendaProduto(),
                'quantidadeStock' => $item->getQuantidadeStock(),
                'isEstocavel' => $item->getIsEstocavel(),
                'quantidadeMinima' => $item->getQuantidadeMinima(),
                'quantidadeCritica' => $item->getQuantidadeCritica(),
                'taxaIva' => $item->getTaxaIva(),
                'quantidade' => $item->getQuantidade(),
            ]);
        }
        return $fatura;
    }


    private function isCart($item)
    {
        $cart = collect($this->items);
        $cart = $cart->firstWhere('produtoId', $item['produtoId']);
        return $cart;
    }


}
