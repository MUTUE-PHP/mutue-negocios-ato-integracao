<?php

namespace App\Http\Controllers\empresa\Produtos;

use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCustoSemPaginacao;
use App\Application\UseCase\Empresa\Produtos\EliminarProduto;
use App\Application\UseCase\Empresa\Produtos\GetProdutos;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPeloCentroCusto;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\empresa\Statu;
use Livewire\WithPagination;

class ProdutoIndexController extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    private $produtoRepository;
    public $vendasOnline = null;
    public $produtoId;
    public $centroCustoId = null;
    public $centrosCusto = [];
    public $filter = [
        'search' => null,
        'vendasOnline' => null,
        'centroCustoId' => null
    ];


    public function mount(){
        $centrosCusto = auth()->user()->centrosCusto;
        if (!$centrosCusto) return redirect()->back();
        $this->centrosCusto = $centrosCusto;
    }

//    public function updatingFilterVendasOnline()
//    {
//        $this->resetPage();
//    }
//    public function updatingFilterCentroCustoId()
//    {
//        $this->resetPage();
//    }
//    public function updatingFilterSearch()
//    {
//        $this->resetPage();
//    }


    public function modalDel($produtoId){
        $this->produtoId = $produtoId;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'deletarProduto',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }
    public function deletarProduto(){
        try {
            $eliminarProduto = new EliminarProduto(new DatabaseRepositoryFactory());
            $eliminarProduto->execute($this->produtoId);
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            return;
        }catch (\Exception $e){
            $this->confirm('Sem permissão para eliminar. Produto contém documento', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

    }
    protected $listeners = [
        'selectedItem','deletarProduto'
    ];
    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->resetPage();
        $this->filter[$item['atributo']] = $item['valor'];
    }
    public function render()
    {
        $data['status'] = Statu::all();
        $this->filter['centroCustoId'] = session()->get('centroCustoId');
        $getprodutos = new GetProdutosPeloCentroCusto(new DatabaseRepositoryFactory());
        $data['produtos'] = $getprodutos->execute($this->filter);
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.produtos.index', $data);
    }
    public function imprimirProdutos()
    {
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "produtos3";
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                    'centroCustoId' => session()->get('centroCustoId')
                ]
            ]
        );
        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
}
