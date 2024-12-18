<?php

namespace App\Http\Controllers\empresa\MunicipiosFrete;
use App\Application\UseCase\Empresa\Categorias\GetCategorias;
use App\Application\UseCase\Empresa\Produtos\CadastrarProduto;
use App\Application\UseCase\GetProvincias;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\CadastrarMunicipioFrete;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Repositories\Empresa\CategoriaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class MunicipioFreteCreateController extends Component
{
    use LivewireAlert;

    public $provincias;
    public $municipio;
    protected $listeners = ['selectedItem'];


    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        $this->municipio[$item['atributo']] = $item['valor'];

    }


    public function setarValor(){
        $this->municipio['provinciaId'] = null;
        $this->municipio['designacao'] = null;
        $this->municipio['valorEntrega'] = null;
        $this->municipio['statusId'] = 1;
    }
    public function mount(){
        $this->setarValor();
        $getProvincias = new GetProvincias(new DatabaseRepositoryFactory());
        $this->provincias = $getProvincias->execute();
        $this->municipio['provinciaId'] = $this->provincias[0]['id'];
    }
    public function render(){
        return view('empresa.municipiosFrete.create');
    }
    public function salvarFrete(){

        $rules = [
            'municipio.designacao' => ['required'],
            'municipio.provinciaId' => ['required'],
            'municipio.valorEntrega' => ['required']
        ];
        $messages = [
            'municipio.designacao.required' => 'Informe o munícipio',
            'municipio.provinciaId.required' => 'Informe a provincia',
            'municipio.valorEntrega.required' => 'Informe o valor de entrega',

        ];
        $this->validate($rules, $messages);
        try {
            DB::beginTransaction();
            $cadastrarMunicipioFrete = new CadastrarMunicipioFrete(new \App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory());
            $output = $cadastrarMunicipioFrete->execute(new Request($this->municipio));
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
