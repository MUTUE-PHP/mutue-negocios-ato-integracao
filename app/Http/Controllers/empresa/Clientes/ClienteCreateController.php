<?php

namespace App\Http\Controllers\empresa\Clientes;

use App\Infra\Traits\TraitRuleUnique;
use App\Models\empresa\Pais;
use App\Models\empresa\TiposCliente;
use App\Repositories\Empresa\ClienteRepository;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ClienteCreateController extends Component
{

    use LivewireAlert;
    use TraitRuleUnique;

    public $cliente;
    private $clienteRepository;
    protected $listeners = ['refresh-me' => '$refresh', 'selectedItem'];

    public function boot(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
        $this->setarValorPadrao();
    }

    public function render()
    {
        $data['paises'] = Pais::all();
        $data['tiposClientes'] = TiposCliente::all();
        return view('empresa.clientes.create', $data);
    }

    public function salvarCliente()
    {
        $rules = [
            'cliente.nome' => 'required',
            'cliente.nif' => [function ($attr, $nif, $fail) {
                if ($this->clienteRepository->getClientePeloNifStore($this->cliente)) {
                    $fail('Cliente já cadastrado');
                }
            }],
            'cliente.tipo_cliente_id' => 'required',
            'cliente.pessoa_contacto' => 'required',
        ];
        $messages = [
            'cliente.nome.required' => 'Informe o nome do cliente',
            'cliente.tipo_cliente_id.required' => 'Informe o tipo cliente',
            'cliente.pessoa_contacto.required' => 'Informe a pessoa de contato',
        ];

        $this->validate($rules, $messages);
        $this->clienteRepository->store($this->cliente);
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
        $this->setarValorPadrao();
    }
    public function setarValorPadrao()
    {
        $this->cliente['nome'] = NULL;
        $this->cliente['email'] = NULL;
        $this->cliente['telefone_cliente'] = NULL;
        $this->cliente['isencaoCargaTransito'] = false;
        $this->cliente['website'] = NULL;
        $this->cliente['endereco'] = NULL;
        $this->cliente['cidade'] = NULL;
        $this->cliente['pais_id'] = 1;
        $this->cliente['nif'] = NULL;
        $this->cliente['tipo_cliente_id'] = "";
        $this->cliente['pessoa_contacto'] = NULL;
        $this->cliente['numero_contrato'] = NULL;
        $this->cliente['taxa_de_desconto'] = 0;
        $this->cliente['limite_de_credito'] = 0;
        $this->cliente['status_id'] = 1;
        $this->cliente['canal_id'] = 2;
        $this->cliente['tipo_conta_corrente'] = 'Nacional';
        $this->cliente['dataContracto'] = Carbon::now()->format('Y-m-d');
    }
}
