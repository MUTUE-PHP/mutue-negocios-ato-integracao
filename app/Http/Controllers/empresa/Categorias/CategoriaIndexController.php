<?php

namespace App\Http\Controllers\empresa\Categorias;

use App\Application\UseCase\Empresa\Categorias\EliminarCategoria;
use App\Application\UseCase\Empresa\Categorias\GetCategorias;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriaIndexController extends Component
{
    use LivewireAlert;
    use WithPagination;

    public $categoriaId;
    public $search = null;
    protected $listeners = ['deletarCategoria'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $getCategorias = new GetCategorias(new DatabaseRepositoryFactory());
        $data['categorias'] = $getCategorias->execute($this->search);
        return view('empresa.categorias.index', $data);
    }

    public function imprimirCategoria()
    {

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;

        $filename = "categorias";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                ]
            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
    public function modalDel($categoriaId)
    {
        $this->categoriaId = $categoriaId;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'deletarCategoria',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }
    public function deletarCategoria($data)
    {
        if ($data['value']) {
            try {
                $deletarCategoria = new EliminarCategoria(new DatabaseRepositoryFactory());
                $deletarCategoria->execute($this->categoriaId);
                $this->confirm('Operação realizada com sucesso', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'success'
                ]);
            } catch (\Throwable $th) {
                $this->confirm('Não permitido eliminar', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
            }
        }
    }

}
