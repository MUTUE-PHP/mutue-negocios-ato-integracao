<?php

namespace App\Http\Controllers\empresa\AtualizacaoEstoque;
use App\Application\UseCase\Empresa\Estoque\GetAtualizacaoEstoque;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Livewire\Component;
use Livewire\WithPagination;

class AtualizacaoEstoqueIndexController extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filtro = [
        'search' => null,
        'centroCustoId' => null,
        'armazemId' => null
    ];
    public function render(){
        $data = [];
        $getAtualizacaoEstoque = new GetAtualizacaoEstoque(new DatabaseRepositoryFactory());
        $data['atualizacoesEstoque'] = $getAtualizacaoEstoque->execute($this->filtro);
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view("empresa.atualizarEstoque.index", $data);
    }
    public function imprimirEstoque($estoqueId)
    {
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "actualizaStock";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                    'actualizaStockId' => $estoqueId
                ]
            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

}
