<?php

namespace App\Http\Controllers\empresa\CartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\CadastrarCartaoCliente;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Clientes\GetClientesSemConsumidorFinal;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\Relatorios\RelatorioCartaoClienteJasper;
use App\Infra\Traits\TraitRuleUnique;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CartaoClienteCreateController extends Component
{

    use TraitRuleUnique;
    use LivewireAlert;
    use RelatorioCartaoClienteJasper;

    public $clienteId;
    public $saldo = 0;
    public $dataEmissao;
    public $dataValidade;
    public $search = null;
    private $clienteRepository;

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
    public function mount(){
        $this->dataEmissao = Carbon::now()->format('Y-m-d');
    }



    public function render()
    {
        $getClientes = new GetClientesSemConsumidorFinal(new DatabaseRepositoryFactory());
        $data['clientes'] = $getClientes->execute();
        return view('empresa.CartaoClientes.create', $data);
    }
    public function emitirCartaoCliente(){

        $request = new Request([
            'clienteId' => $this->clienteId,
            'dataEmissao' => $this->dataEmissao,
            'dataValidade' => $this->dataValidade,
            'saldo' => $this->saldo,
        ]);

        $messages = [
            'clienteId.required' => 'Informe o cliente',
            'dataEmissao.required' => 'Informe a data de emissão',
            'dataValidade.required' => 'Informe a data de válidade'
        ];

        $rules = [
            'clienteId' => ['required', function ($attr, $clienteId, $fail) {
                $unique = TraitRuleUnique::unique3("cartao_clientes", 'clienteId',$clienteId, null);
                if ($unique) $fail('Cliente já emitiu o cartão');
            }],
            'dataEmissao' => ['required'],
            'dataValidade' => ['required', function ($attr, $dataValidade, $fail) use ($request) {
                if ($request->dataEmissao > $dataValidade) {
                    $fail('Data emissão deve ser menor a data de válidade');
                }
            }],
        ];
        $this->validate($rules, $messages);

        $emitirCartaoCliente = new CadastrarCartaoCliente(new DatabaseRepositoryFactory());
        $cartaoCliente = $emitirCartaoCliente->execute(new Request([
            'clienteId' => $this->clienteId,
            'saldo' => $this->saldo,
            'dataEmissao' => $this->dataEmissao,
            'dataValidade' => $this->dataValidade
        ]));

        $this->imprimirCartaoCliente($cartaoCliente->id);
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);

    }

}
