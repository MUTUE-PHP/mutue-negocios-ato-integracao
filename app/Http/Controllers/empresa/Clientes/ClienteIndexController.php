<?php

namespace App\Http\Controllers\empresa\Clientes;

use App\Http\Controllers\empresa\ReportShowController;
use App\Models\empresa\Pais;
use App\Models\empresa\TiposCliente;
use App\Repositories\Empresa\ClienteRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ClienteIndexController extends Component
{

    use LivewireAlert;

    public $cliente;
    public $clienteId;
    public $search = null;
    private $clienteRepository;

    protected $listeners = ['deletarCliente'];




    public function boot(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function render()
    {
        $data['clientes'] = $this->clienteRepository->getClientes($this->search);
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.clientes.index', $data);
    }
    public function imprimirClientes(){
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "clientes";
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
    public function modalDel($clienteId)
    {
        $this->clienteId = $clienteId;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'deletarCliente',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }
    public function deletarCliente($data)
    {
        if ($data['value']) {
            $faturas = DB::table('facturas')->where('clienteId', $this->clienteId)->first();
            $recibos = DB::table('recibos')->where('clienteId', $this->clienteId)->first();

            if($faturas || $recibos){
                $this->confirm('Não permitido eliminar, o Cliente emitiu documento', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
            DB::table('clientes')->where('id', $this->clienteId)->delete();
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            return;
        }
    }

}
