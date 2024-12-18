<?php

namespace App\Http\Controllers\empresa\ComunasFrete;
use App\Application\UseCase\GetProvincias;
use App\Application\UseCase\VendasOnline\ComunasFrete\CadastrarComunaFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\CadastrarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFretePelaProvincia;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory as DatabaseRepositoryFactoryVendaOnline;
use App\Repositories\Empresa\CategoriaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComunaFreteCreateController extends Component
{
    use LivewireAlert;

    public $provincias;
    public $comuna;
    protected $listeners = ['selectedItem'];


    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        $this->comuna[$item['atributo']] = $item['valor'];

    }


    public function setarValor(){
        $this->comuna['provinciaId'] = null;
        $this->comuna['municipioId'] = null;
        $this->comuna['designacao'] = null;
        $this->comuna['valorEntrega'] = null;
        $this->comuna['statusId'] = 1;
    }
    public function mount(){
        $this->setarValor();
        $getProvincias = new GetProvincias(new DatabaseRepositoryFactory());
        $this->provincias = $getProvincias->execute();
        $this->comuna['provinciaId'] = $this->provincias[0]['id'];
    }
    public function render(){
        $getMunicipios = new GetMunicipiosFretePelaProvincia(new DatabaseRepositoryFactoryVendaOnline());
        $data['municipios'] = $getMunicipios->execute($this->comuna['provinciaId']);
        return view('empresa.comunasFrete.create', $data);
    }
    public function salvarFrete(){

        $rules = [
            'comuna.designacao' => ['required'],
            'comuna.provinciaId' => ['required'],
            'comuna.municipioId' => ['required'],
            'comuna.valorEntrega' => ['required']
        ];
        $messages = [
            'comuna.designacao.required' => 'Informe o munícipio',
            'comuna.provinciaId.required' => 'Informe a provincia',
            'comuna.municipioId.required' => 'Informe o municipio',
            'comuna.valorEntrega.required' => 'Informe o valor de entrega',

        ];
        $this->validate($rules, $messages);
        try {
            DB::beginTransaction();
            $cadastrarComunaFrete = new CadastrarComunaFrete(new \App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory());
            $output = $cadastrarComunaFrete->execute(new Request($this->comuna));
            DB::commit();
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->mount();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }
}
