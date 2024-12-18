<?php

namespace App\Http\Controllers\empresa\Inventarios;
use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCustoUserAutenticado;
use App\Application\UseCase\Empresa\Inventarios\EmitirInventario;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPorArmazem;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPorArmazemECentroCusto;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class InventarioCreateController extends Component
{

    use LivewireAlert;

    public $centrosCusto;
    public $armazens;
    public $centroCustoId;
    public $armazemId;
    public $produtosAtual = [];
    public $inventario = [
        "centroCustoId" => null,
        "armazemId" => null,
        "produtos" => []
    ];
    protected $rules = [
        'produtos.*.quantidadeAtual' => 'required',
    ];
    public $quantidade;
    public $produtos;
    public $filter = [
        'search' => null,
        'armazemId' => null,
        'centroCustoId' => null
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
        $this->filter[$item['atributo']] = $item['valor'];
    }

    public function mount()
    {
        $getCentrosCusto = new GetCentrosCustoUserAutenticado(new DatabaseRepositoryFactory());
        $this->centroscusto = $getCentrosCusto->execute();
        $this->filter['centroCustoId'] = session()->get('centroCustoId');

        $getArmazens = new GetArmazens(new DatabaseRepositoryFactory());
        $this->armazens = $getArmazens->execute();
        $this->filter['armazemId'] = $this->armazens[0]['id'];
    }

    public function render($quantidade = null, $key = null)
    {
        $getProdutos = new GetProdutosPorArmazemECentroCusto(new DatabaseRepositoryFactory());
        $this->produtos = $getProdutos->execute($this->filter);
        return view('empresa.inventarios.create');
    }

    public function emitirInventario()
    {
        if(count($this->produtosAtual) <= 0){
            $this->confirm('Não teve alteração nos items do inventário', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        foreach ($this->produtos as $key => $produto) {
            if (array_key_exists($key, $this->produtosAtual)) {
                $this->produtos[$key]['quantidadeAtual'] = $this->produtosAtual[$key]['quantidade'];
            }
        }

        $emitirInventario = new EmitirInventario(new DatabaseRepositoryFactory());
        $inventarioId = $emitirInventario->execute($this->produtos);
        return $this->imprimirInventario2($inventarioId);
    }
    public function imprimirInventario2($inventarioId){

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "inventario";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                    'inventarioId' => $inventarioId
                ]
            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }


    public function updatedProdutos($quantidade, $key)
    {

        $this->render($quantidade, $key);
    }

    public function alterarExistencia($id, $key)
    {

        dd($key);
        $item = $this->produtos->firstWhere('id', $id);
        $this->produtos[$key]['quantidadeAtual'] = 0;
    }

//    public function alterarExistencia($key)
//    {
//
//        $this->inventario['centroCustoId'] = $this->filter['centroCustoId'];
//        $this->inventario['armazemId'] = $this->filter['armazemId'];
//        $this->produtos[$key]['existenciaAtual'] = $this->quantidade;
//
//    }

    public function imprimirInventario()
    {

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "inventarioExistenciaBranco";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                    'centroCustoId' => $this->filter['centroCustoId'],
                    'armazemId' => $this->filter['armazemId'],
                ]
            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();


    }

}
