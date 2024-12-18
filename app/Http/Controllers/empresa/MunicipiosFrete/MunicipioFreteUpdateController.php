<?php

namespace App\Http\Controllers\empresa\MunicipiosFrete;

use App\Application\UseCase\GetProvincias;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\AtualizarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\CadastrarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipioFrete;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class MunicipioFreteUpdateController extends Component
{
    use LivewireAlert;

    public $municipio;
    public $provincias;

    protected $listeners = ['selectedItem'];


    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        $this->municipio[$item['atributo']] = $item['valor'];
    }


    public function rules(){
        return [
            'municipio.designacao' => ['required'],
            'municipio.provinciaId' => ['required'],
            'municipio.valorEntrega' => ['required']
        ];
    }
    public function messages(){
        return [
            'municipio.designacao.required' => 'Informe o munícipio',
            'municipio.provinciaId.required' => 'Informe a provincia',
            'municipio.valorEntrega.required' => 'Informe o valor de entrega'
        ];
    }
    public function mount($municipioId){

        $this->municipio['id'] = $municipioId;
        $getMunicipioFrete = new GetMunicipioFrete(new DatabaseRepositoryFactory());
        $municipio = $getMunicipioFrete->execute($municipioId);
        $this->municipio['designacao'] = $municipio->designacao;
        $this->municipio['provinciaId'] = $municipio->cidade_id;
        $this->municipio['statusId'] = $municipio->status_id;
        $this->municipio['valorEntrega'] = $municipio->valor_entrega;

        $getProvincias = new GetProvincias(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
        $this->provincias = $getProvincias->execute();
    }
    public function render(){
        return view('empresa.municipiosFrete.update');
    }
    public function updateFrete(){
        $this->validate($this->rules(), $this->messages());
        try {
            DB::beginTransaction();
            $atualizarMunicipioFrete = new AtualizarMunicipioFrete(new DatabaseRepositoryFactory());
            $output = $atualizarMunicipioFrete->execute(new Request($this->municipio), $this->municipio['id']);
            DB::commit();
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->mount($this->municipioId);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }

}
