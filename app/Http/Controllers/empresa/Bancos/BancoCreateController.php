<?php

namespace App\Http\Controllers\empresa\Bancos;

use App\Http\Controllers\TraitLogAcesso;
use App\Models\empresa\Banco;
use App\Repositories\Empresa\BancoRepository;
use App\Repositories\Empresa\ContaRepository;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BancoCreateController extends Component
{

    use LivewireAlert;
    use TraitLogAcesso;

    public $banco;
    public $contas;
    private $bancoRepository;
    private $contaRepository;

    protected $listeners = ['refresh-me' => '$refresh', 'selectedItem'];

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        $this->banco[$item['atributo']] = $item['valor'];
    }

    public function mount(){
       // dd('teste');
    }


    public function __construct()
    {
        $this->setarValorPadrao();
    }

    public function boot(BancoRepository $bancoRepository, ContaRepository $contaRepository)
    {
        $this->bancoRepository = $bancoRepository;
        $this->contaRepository = $contaRepository;
    }

    public function render()
    {
        $this->contas = $this->contaRepository->getAll();
        return view('empresa.bancos.create');
    }
    public function salvarBanco()
    {
        $sequencia = $this->contaRepository->getSequencia(new Banco());
        $this->banco['subconta'] = $this->banco['conta']."1.1.".$sequencia;
        $this->banco['sequencia'] = $sequencia;
        $conta = $this->contaRepository->getConta($this->contas,$this->banco['conta']);

        $rules = [
            'banco.designacao' => ["required"],
            'banco.sigla' => "required",
            'banco.iban' => "required",
            'banco.conta' => "required",
            'banco.subconta' => "required",
            'banco.status_id' => "required",
        ];
        $messages = [
            'banco.designacao.required' => 'campo obrigatório',
            'banco.sigla.required' => 'campo obrigatório',
            'banco.conta.required' => 'campo obrigatório',
            'banco.iban.required' => 'campo obrigatório',
            'banco.status_id.required' => 'campo obrigatório',
            'banco.subconta.required' => 'campo obrigatório'
        ];
        $this->validate($rules, $messages);
        $this->bancoRepository->store($this->banco);

        $data =  [
            'empresa_id' => 1,
            'conta_id' => $conta['conta_id'],
            'tipo' => 'M',
            'numero' => $this->banco['subconta'],
            'descricao' => $this->banco['designacao'],
            'designacao' => $this->banco['designacao'],
            'tipo_instituicao' => 'mutue_negocios',
            'instituicao_id' => auth()->user()->empresa_id
        ];
        $this->contaRepository->create($data);
        $this->setarValorPadrao();
        $this->logAcesso();
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }

    public function setarValorPadrao()
    {
        $this->banco['designacao'] = NULL;
        $this->banco['sigla'] = NULL;
        $this->banco['conta'] = NULL;
        $this->banco['sequencia'] = NULL;
        $this->banco['subconta'] = NULL;
        $this->banco['num_conta'] = NULL;
        $this->banco['iban'] = NULL;
        $this->banco['swift'] = NULL;
        $this->banco['canal_id'] = 2;
        $this->banco['status_id'] = 1;
        $this->banco['tipo_user_id'] = 2;
    }
}
