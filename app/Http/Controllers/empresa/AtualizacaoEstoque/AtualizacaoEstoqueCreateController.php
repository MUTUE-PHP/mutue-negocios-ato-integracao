<?php

namespace App\Http\Controllers\empresa\AtualizacaoEstoque;

use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCustoSemPaginacao;
use App\Application\UseCase\Empresa\Estoque\AtualizarEstoque;
use App\Application\UseCase\Empresa\Estoque\GetAtualizacaoEstoque;
use App\Application\UseCase\Empresa\Produtos\GetProdutoArmazemIdPeloCentroCustoId;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AtualizacaoEstoqueCreateController extends Component
{
    use LivewireAlert;

    public $atualizacaoStock;
    public $armazens = [];
    public $centrosCusto = [];


    protected $listeners = [
        'selectedItem'
    ];

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        $this->atualizacaoStock[$item['atributo']] = $item['valor'];
        if ($item['atributo'] == 'armazemId' || $item['atributo'] == 'centroCustoId') {
            $this->atualizacaoStock['produtoId'] = null;
            $this->atualizacaoStock['quantidadeAnterior'] = null;
        }
    }

    public function mount()
    {
        $getArmazens = new GetArmazens(new DatabaseRepositoryFactory());
        $this->armazens = $getArmazens->execute();
        $this->atualizacaoStock['armazemId'] = $this->armazens[0]['id'];
        $centrosCusto = auth()->user()->centrosCusto;
        $this->centrosCusto = $centrosCusto;
        $this->atualizacaoStock['centroCustoId'] = session()->get('centroCustoId');
        $this->atualizacaoStock['produtoId'] = null;
        $this->atualizacaoStock['quantidadeAnterior'] = null;
        $this->atualizacaoStock['quantidadeNova'] = null;
        $this->atualizacaoStock['descricao'] = null;
    }

    public function render()
    {

        $getProdutos = new GetProdutoArmazemIdPeloCentroCustoId(new DatabaseRepositoryFactory());
        $existencias = $getProdutos->execute($this->atualizacaoStock['centroCustoId'], $this->atualizacaoStock['armazemId']);


        $data['existencias'] = $existencias;
        if ($this->atualizacaoStock['produtoId']) {
            $this->atualizacaoStock['quantidadeAnterior'] = $this->buscarQuantidadeAnterior($existencias);
        }
        return view("empresa.atualizarEstoque.create", $data);
    }

    public function atualizarEstoque()
    {
        $request = new Request($this->atualizacaoStock);

        $messages = [
            'atualizacaoStock.centroCustoId.required' => 'Informe o centro de custo',
            'atualizacaoStock.armazemId.required' => 'Informe o armazém',
            'atualizacaoStock.produtoId.required' => 'Informe o produto',
            'atualizacaoStock.quantidadeNova.required' => 'Informe a nova quantidade',
        ];
        $rules = [
            'atualizacaoStock.centroCustoId' => ['required'],
            'atualizacaoStock.armazemId' => ['required'],
            'atualizacaoStock.produtoId' => ['required'],
            'atualizacaoStock.quantidadeNova' => ['required', function ($attr, $quantidade, $fail) {
                if ($quantidade == $this->atualizacaoStock['quantidadeAnterior']) {
                    $fail("Informe uma quantidade diferente da atual");
                }
            }],

        ];
        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $atualizarEstoque = new AtualizarEstoque(new DatabaseRepositoryFactory());
            $output = $atualizarEstoque->execute($request);
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            DB::commit();
        } catch (\Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }
    public function buscarQuantidadeAnterior($existencias)
    {
        foreach ($existencias as $existencia) {
            if ($existencia->produto_id == $this->atualizacaoStock['produtoId']) {
                return $existencia->quantidade;
            }
        }
    }

}
