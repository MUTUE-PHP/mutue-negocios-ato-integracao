<?php

namespace App\Http\Controllers\admin\ResetarSenhaEmpresa;

use App\Application\UseCase\Admin\Cliente\GetClientes;
use App\Application\UseCase\Admin\ResetarSenhaCliente;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ResetarSenhaEmpresaCreateController extends Component
{
    use LivewireAlert;

    public $empresaId;

    public $newSenha;

    protected $listeners = [
        'selectedItem'
    ];
    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->{$item['atributo']} = $item['valor'];
    }


    public function render()
    {
        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $data['clientes'] = $getClientes->execute();

        return view('admin.resetarSenhaEmpresa.create', $data)->layout('layouts.appAdmin');
    }
    public function atualizarSenha()
    {
        $request = new Request([
            'clienteId' => $this->empresaId,
            'novaSenha' => $this->newSenha
        ]);

        try {
            $resetarSenhaCliente = new ResetarSenhaCliente(new DatabaseRepositoryFactory());
            $resetarSenhaCliente->execute($request);
            $this->empresaId = null;
            $this->newSenha = null;
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
    }
}
