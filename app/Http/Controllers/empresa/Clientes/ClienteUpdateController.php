<?php

namespace App\Http\Controllers\empresa\Clientes;

use App\Http\Requests\StoreUpdateClienteRequest;
use App\Infra\Traits\TraitRuleUnique;
use App\Models\empresa\Pais;
use App\Models\empresa\TiposCliente;
use App\Repositories\Empresa\ClienteRepository;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ClienteUpdateController extends Component
{

    use LivewireAlert;
    use StoreUpdateClienteRequest;
    use TraitRuleUnique;

    public $cliente;
    public $clienteId;
    private $clienteRepository;

    public function boot(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
        $this->setarValorPadrao();
    }

    public function mount($clienteId)
    {
        $this->clienteId = $clienteId;
        $cliente = $this->clienteRepository->getCliente($clienteId);
        $this->cliente['id'] = $cliente['id'];
        $this->cliente['nome'] = $cliente['nome'];
        $this->cliente['email'] = $cliente['email'];
        $this->cliente['telefone_cliente'] = $cliente['telefone_cliente'];
        $this->cliente['website'] = $cliente['website'];
        $this->cliente['endereco'] = $cliente['endereco'];
        $this->cliente['cidade'] = $cliente['cidade'];
        $this->cliente['pais_id'] = $cliente['pais_id'];
        $this->cliente['nif'] = $cliente['nif'];
        $this->cliente['tipo_cliente_id'] = $cliente['tipo_cliente_id'];
        $this->cliente['pessoa_contacto'] = $cliente['pessoa_contacto'];
        $this->cliente['numero_contrato'] = $cliente['numero_contrato'];
        $this->cliente['centroCustoId'] = $cliente['centroCustoId'];
        $this->cliente['data_contrato'] = $cliente['data_contrato']??null;
        $this->cliente['isencaoCargaTransito'] = $cliente['isencaoCargaTransito'] == 'Y' ? true : false;
    }


    public function render()
    {
        $data['paises'] = Pais::all();
        $data['tiposClientes'] = TiposCliente::all();
        return view('empresa.clientes.edit', $data);
    }

    public function updateCliente()
    {
        $this->validate($this->rules(), $this->messages());
        $this->clienteRepository->update($this->cliente, $this->cliente['id']);
        $this->alert('success', 'Operação realizada com sucesso');
    }

    public function setarValorPadrao()
    {
        $this->cliente['nome'] = NULL;
        $this->cliente['email'] = NULL;
        $this->cliente['telefone_cliente'] = NULL;
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
        $this->cliente['data_contrato'] = Carbon::now()->format('Y-m-d');
    }
}
