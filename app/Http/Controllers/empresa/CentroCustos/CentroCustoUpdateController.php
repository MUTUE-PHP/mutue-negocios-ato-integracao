<?php

namespace App\Http\Controllers\empresa\CentroCustos;

use App\Application\UseCase\Empresa\CentrosDeCusto\AtualizarCentroCusto;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentroCustoUuid;
use App\Http\Requests\UpdateCentroCustoRequest;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;


class CentroCustoUpdateController extends Component
{
    use WithFileUploads;
    use UpdateCentroCustoRequest;
    use LivewireAlert;


    public $centroCusto;
    public $centroCustoId;
    protected $listeners = ['refreshComponent' => '$refresh'];


    public function mount($uuid)
    {
        $this->centroCustoId = $uuid;
        $getCentroCusto = new GetCentroCustoUuid(new DatabaseRepositoryFactory());
        $centroCusto = $getCentroCusto->execute($uuid);

        $this->centroCusto['nome'] = $centroCusto['nome'];
        $this->centroCusto['statusId'] = $centroCusto['status_id'];
        $this->centroCusto['endereco'] = $centroCusto['endereco'];
        $this->centroCusto['nif'] = $centroCusto['nif'];
        $this->centroCusto['cidade'] = $centroCusto['cidade'];
        $this->centroCusto['logotipo'] = null;
        $this->centroCusto['fileAlvara'] = null;
        $this->centroCusto['fileNif'] = null;
        $this->centroCusto['email'] = $centroCusto['email'];
        $this->centroCusto['website'] = $centroCusto['website'];
        $this->centroCusto['telefone'] = $centroCusto['telefone'];
        $this->centroCusto['pessoaContato'] = $centroCusto['pessoa_de_contacto'];
        $this->centroCusto['antLogotipo'] = $centroCusto['logotipo'];
        $this->centroCusto['antFileNif'] = $centroCusto['file_nif'];
        $this->centroCusto['antFileAlvara'] = $centroCusto['file_alvara'];


        if (!$this->centroCusto) {
            return redirect()->route('centroCusto.index');
        }
    }


    public function render()
    {
        return view("empresa.centroCustos.update");
    }

    public function update()
    {

        $this->validate($this->rules($this->centroCustoId), $this->messages());

        try {
            $updateCentroCusto = new AtualizarCentroCusto(new DatabaseRepositoryFactory());
            $updateCentroCusto->execute(new Request($this->centroCusto), $this->centroCustoId);
            $this->confirm('Operação realizada com sucesso', ['showConfirmButton' => false, 'showCancelButton' => false, 'icon' => 'success']);
            $this->mount($this->centroCustoId);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

    }

    public function rules($uuid = null)
    {
        return [
            'centroCusto.nome' => ['required', function ($attribute, $value, $fail) use ($uuid) {
                $empresa = DB::connection('mysql2')->table('centro_custos')
                    ->where('nome', $value)->where('uuid', '!=', $uuid)
                    ->where('empresa_id', auth()->user()->empresa->id)
                    ->first();
                if ($empresa) {
                    $fail('O nome já se encontra cadastrado');
                }
            }],
            'centroCusto.nif' => ['required'],
            'centroCusto.cidade' => ['required'],
            'centroCusto.statusId' => ['required'],
            'centroCusto.endereco' => ['required'],
            'centroCusto.email' => ['required'],
            'centroCusto.telefone' => ['required'],
            'centroCusto.website' => '',
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
