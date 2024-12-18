<?php

namespace App\Http\Controllers\empresa\CartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\AtualizarCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\BaixarCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientes;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\Relatorios\RelatorioCartaoClienteJasper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CartaoClienteIndexController extends Component
{

    use LivewireAlert;
    use RelatorioCartaoClienteJasper;

    public $search = null;
    public $cartaoCliente;

    public function render()
    {
        $getCartaoClientes = new GetCartaoClientes(new DatabaseRepositoryFactory());
        $data['cartaoClientes'] = $getCartaoClientes->execute($this->search);
        $this->dispatchBrowserEvent('reloadTableJquery');

        return view('empresa.CartaoClientes.index', $data);
    }
    public function showModalUpdateCartaoCliente($cartaoCliente){
        $this->cartaoCliente['id'] = $cartaoCliente['id'];
        $this->cartaoCliente['clienteId'] = $cartaoCliente['clienteId'];
        $this->cartaoCliente['numeracaoSequencia'] = $cartaoCliente['numeracaoSequencia'];
        $this->cartaoCliente['nomeCliente'] = Str::upper($cartaoCliente['cliente']['nome']);
        $this->cartaoCliente['numeroCartao'] = $cartaoCliente['numeroCartao'];
        $this->cartaoCliente['saldo'] = number_format($cartaoCliente['saldo'], 2,',','.');
        $this->cartaoCliente['dataEmissao'] = date_format(date_create($cartaoCliente['dataEmissao']), 'Y-m-d');
        $this->cartaoCliente['dataValidade'] = date_format(date_create($cartaoCliente['dataValidade']), 'Y-m-d');
    }
    public function atualizarCartaoCliente(){

        $request = new Request($this->cartaoCliente);
        $messages = [
            'cartaoCliente.clienteId.required' => 'Informe o cliente',
            'cartaoCliente.dataEmissao.required' => 'Informe a data de emissão',
            'cartaoCliente.dataValidade.required' => 'Informe a data de válidade'
        ];
        $rules = [
            'cartaoCliente.clienteId' => ['required'],
            'cartaoCliente.dataEmissao' => ['required'],
            'cartaoCliente.dataValidade' => ['required', function ($attr, $dataValidade, $fail) use ($request) {
                if ($request->dataEmissao > $dataValidade) {
                    $fail('Data emissão deve ser menor a data de válidade');
                }
            }],
        ];

        $this->validate($rules, $messages);

        $atualizarCartaoCliente = new AtualizarCartaoCliente(new DatabaseRepositoryFactory());
        $cartaoCliente = $atualizarCartaoCliente->execute($request);

        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }
    public function baixarCartaoCliente($cartaoClienteId){
        $this->imprimirCartaoCliente($cartaoClienteId);
    }
    public function baixarHistoricoCartaoCliente($clienteId){

        $this->imprimirHistoricoCartaoCliente($clienteId);

    }

}
