<?php

namespace App\Http\Controllers\empresa\MunicipiosFrete;

use App\Application\UseCase\VendasOnline\MunicipiosFrete\EliminarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFrete;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Models\empresa\Pais;
use App\Models\empresa\TiposCliente;
use App\Repositories\Empresa\CategoriaRepository;
use App\Repositories\Empresa\FornecedorRepository;
use App\Repositories\Empresa\MarcaRepository;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class MunicipioFreteIndexController extends Component
{
    use LivewireAlert;
    public $search;
    public $municipioId;

    protected $listeners = ['refresh' => '$refresh','deletarMunicipio'];



    public function render(){
        $getMunicipiosFrete = new GetMunicipiosFrete(new DatabaseRepositoryFactory());
        $data['municipiosFrete'] = $getMunicipiosFrete->execute($this->search);
        return view('empresa.municipiosFrete.index', $data);
    }
    public function modalDel($categoriaId)
    {
        $this->municipioId = $categoriaId;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'deletarMunicipio',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }
    public function deletarMunicipio($data)
    {

        if ($data['value']) {
            try {

                $deletarMunicipio = new EliminarMunicipioFrete(new DatabaseRepositoryFactory());
                $deletarMunicipio->execute($this->municipioId);
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
        $this->emitSelf('refresh');
    }
    public function imprimirFretes(){
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;

        $filename = "fretesMunicipio";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'nomeEmpresa' => auth()->user()->empresa->nome,
                    'enderecoEmpresa' => auth()->user()->empresa->endereco,
                    'nifEmpresa' => auth()->user()->empresa->nif,
                    'emailEmpresa' => auth()->user()->empresa->email,
                    'telefoneEmpresa' => auth()->user()->empresa->pessoal_Contacto,
                    'diretorio' => $logotipo,
                ]

            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
}
