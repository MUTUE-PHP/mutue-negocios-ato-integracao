<?php

namespace App\Http\Controllers\empresa\Inventarios;
use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCustoUserAutenticado;
use App\Application\UseCase\Empresa\Inventarios\GetInventarios;
use App\Application\UseCase\Empresa\Produtos\GetProdutoArmazemIdPeloCentroCustoId;
use App\Application\UseCase\Empresa\Produtos\GetProdutosPorArmazemECentroCusto;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Livewire\Component;

class InventarioIndexController extends Component
{
    public $filtro = [];
    public $armazemId;
    public $centroCustoId;
    public $armazens;
    public $search = null;

    public function mount(){

        $getArmazens = new GetArmazens(new DatabaseRepositoryFactory());
        $armazens = $getArmazens->execute();
        $this->armazens = $armazens;
        $this->armazemId = $armazens[0]['id'];
        $this->centroCustoId = session()->get('centroCustoId');
    }

    public function render(){

        $getInventarios = new GetInventarios(new DatabaseRepositoryFactory());
        $inventarios = $getInventarios->execute($this->filtro);

        $getCentrosCusto = new GetCentrosCustoUserAutenticado(new DatabaseRepositoryFactory());
        $getCentrosCusto = $getCentrosCusto->execute();
        $data['centrosCusto'] = $getCentrosCusto;
        $filtro = [
            'centroCustoId' => $this->centroCustoId,
            'armazemId' => $this->armazemId,
            'search' => $this->search
        ];
        $getProdutos = new GetProdutosPorArmazemECentroCusto(new DatabaseRepositoryFactory());
        $data['existenciaStock'] = $getProdutos->execute($filtro);

        $data['centroCustoCheck'] = session()->get('centroCustoId');
        $data['inventarios'] = $inventarios;
        return view('empresa.inventarios.indexCopia', $data);
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
}
