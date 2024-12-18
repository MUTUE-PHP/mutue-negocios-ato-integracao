<?php

namespace App\Http\Controllers\empresa\CentroCustos;
use App\Application\UseCase\Empresa\CentrosDeCusto\CadastrarCentroCusto;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class CentroCustoCreateController extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $centroCusto;

    protected $listeners = ['refresh-me' => '$refresh'];

    public function mount() {
        $this->centroCusto['logotipo'] = null;
        $this->centroCusto['logotipo'] = null;
        $this->centroCusto['statusId'] = 1;
    }

    public function render()
    {
        return view("empresa.centroCustos.create");
    }
    public function store(){
        $this->validate($this->rules(), $this->messages());
        try {
            DB::beginTransaction();
            $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
            $cadastrarCentroCusto->execute(new Request($this->centroCusto));
            $this->confirm('Operação realizada com sucesso', ['showConfirmButton' => false, 'showCancelButton' => false, 'icon' => 'success']);
            $this->reset();
            DB::commit();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();
        }
    }

    public function rules()
    {
        return [
            'centroCusto.nome' => ['required',  function ($attribute, $value, $fail) {
                $empresa = DB::connection('mysql2')->table('centro_custos')
                    ->where('nome', $value)->first();
                if ($empresa && $empresa->id != auth()->user()->empresa->id) {
                    $fail('O ' . $attribute . ' já se encontra cadastrado');
                }
            }],
            'centroCusto.nif' => ['required'],
            'centroCusto.cidade' => ['required'],
            'centroCusto.statusId' => ['required'],
            'centroCusto.endereco' => ['required'],
            'centroCusto.email' => ['required'],
            'centroCusto.telefone' => ['required'],
            'centroCusto.website'=> '',
            'centroCusto.pessoa_de_contacto' => ''
        ];
    }
    public function messages()
    { 
        return [
            'centroCusto.nome.required' => 'Nome é obrigatório',
            'centroCusto.nome.unique' => 'Nome já cadastrado',
            'centroCusto.cidade.required' => 'Cidade é obrigatório',
            'centroCusto.endereco.required' => 'Endereço é obrigatório',
            'centroCusto.email.required' => 'E-mail é obrigatório',
            'centroCusto.nif.required' => 'NIF é obrigatório',
            'centroCusto.telefone.required' => 'Telefone é obrigatório',

        ];
    }

}
