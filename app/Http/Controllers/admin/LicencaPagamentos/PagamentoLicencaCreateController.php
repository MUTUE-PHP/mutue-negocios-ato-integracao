<?php

namespace App\Http\Controllers\admin\LicencaPagamentos;

use App\Application\UseCase\Admin\Banco\GetBancos;
use App\Application\UseCase\Admin\Cliente\GetClientes;
use App\Application\UseCase\Admin\FormasPagamento\GetFormasPagamento;
use App\Application\UseCase\Admin\Licenca\GetLicencas;
use App\Application\UseCase\Admin\PagarLicenca;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class PagamentoLicencaCreateController extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    private $clienteRepository;
    public $search;
    public $empresas;
    public $licencas;
    public $pagamento;
    public $formasPagamento;
    public $bancos;

    protected $listeners = [
        'selectedItem'
    ];

    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        if($item['atributo'] == 'licencaId' && $item['valor'] == 4){
            $this->pagamento['quantidade'] = 1;
        }
        $this->pagamento[$item['atributo']] = $item['valor'];
    }
    public function mount()
    {
        $this->setarValorInicial();
        $clientes = new GetClientes(new DatabaseRepositoryFactory());
        $this->empresas = $clientes->execute();
        $licencas = new GetLicencas(new DatabaseRepositoryFactory());
        $this->licencas = $licencas->execute();
        $formasPagamento = new GetFormasPagamento(new DatabaseRepositoryFactory());
        $this->formasPagamento = $formasPagamento->execute();
        $bancos = new GetBancos(new DatabaseRepositoryFactory());
        $this->bancos = $bancos->execute();
    }

    public function render()
    {
        $data = [];
        return view('admin.pagamentoLicencas.create', $data)->layout('layouts.appAdmin');
    }
    public function updatedPagamentoLicencaId($licencaId){
        dd($licencaId);
    }

    public function enviarPagamento()
    {
        $this->validate($this->rules(), $this->messages());
        try {
            DB::beginTransaction();
            $pagarLicenca = new PagarLicenca(new DatabaseRepositoryFactory());
            $output = $pagarLicenca->execute(new Request($this->pagamento));
            DB::commit();
            $this->confirm('Licença activa com sucesso. Foram enviadas todas as informações por email', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->setarValorInicial();
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->confirm($e->getMessage(), [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
    }
    public function rules()
    {
        return [
            'pagamento.empresaId' => "required",
            'pagamento.licencaId' => "required",
            'pagamento.numeroOperacaoBancaria' => "required",
            'pagamento.dataPagamentoBanco' => "required",
            'pagamento.formaPagamentoId' => "required",
            'pagamento.contaMovimentadaId' => "required",
            'pagamento.comprovativoBancario' => "required|mimes:jpg,jpeg,png",
        ];
    }
    public function messages()
    {
        return [
            'pagamento.empresaId.required' => 'Informe a empresa',
            'pagamento.licencaId.required' => 'Informe a licença',
            'pagamento.numeroOperacaoBancaria.required' => 'Informe o número de operação bancária',
            'pagamento.dataPagamentoBanco.required' => 'Informe a data de pagamento no banco',
            'pagamento.formaPagamentoId.required' => 'Informe a forma de pagamento',
            'pagamento.contaMovimentadaId.required' => 'Informe a conta movimentada',
            'pagamento.comprovativoBancario.required' => 'Informe o comprovativo bancario',
        ];
    }
    public function setarValorInicial()
    {
        $this->pagamento['empresaId'] = null;
        $this->pagamento['licencaId'] = null;
        $this->pagamento['numeroOperacaoBancaria'] = null;
        $this->pagamento['dataPagamentoBanco'] = null;
        $this->pagamento['formaPagamentoId'] = null;
        $this->pagamento['quantidade'] = 1;
        $this->pagamento['contaMovimentadaId'] = null;
        $this->pagamento['comprovativoBancario'] = null;
        $this->pagamento['observacao'] = null;
    }
}
