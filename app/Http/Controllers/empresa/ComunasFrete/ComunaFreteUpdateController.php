<?php

namespace App\Http\Controllers\empresa\ComunasFrete;

use App\Application\UseCase\GetProvincias;
use App\Application\UseCase\VendasOnline\ComunasFrete\AtualizarComunaFrete;
use App\Application\UseCase\VendasOnline\ComunasFrete\GetComunaFrete;
use App\Application\UseCase\VendasOnline\ComunasFrete\GetComunasFretePeloMunicipio;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\AtualizarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\CadastrarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFretePelaProvincia;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ComunaFreteUpdateController extends Component
{
    use LivewireAlert;

    public $comuna;
    public $provincias;
    public $municipios;
    public $comunas;

    protected $listeners = ['selectedItem'];


    public function hydrate()
    {
        $this->emit('select2');
    }

    public function selectedItem($item)
    {
        $this->comuna[$item['atributo']] = $item['valor'];

        if($item['atributo'] == 'provinciaId'){
            $this->comuna['municipioId'] = null;
        }

    }


    public function rules(){
        return [
            'comuna.designacao' => ['required', function($attr, $comuna, $fail){

               $temExiste = DB::connection('mysql2')->table('comunas')
                   ->where('designacao', $comuna)
                   ->where('municipioId', $this->comuna['municipioId'])
                   ->where('id', '!=',$this->comuna['id'])->first();
               if($temExiste){
                   $fail("Comuna com municipio já cadastrado");
               }
            }],
            'comuna.provinciaId' => ['required'],
            'comuna.municipioId' => ['required'],
            'comuna.valorEntrega' => ['required']
        ];
    }
    public function messages(){
        return [
            'comuna.designacao.required' => 'Informe a comuna',
            'comuna.provinciaId.required' => 'Informe a provincia',
            'comuna.valorEntrega.required' => 'Informe o valor de entrega',
            'comuna.municipioId.required' => 'Informe o municipio'
        ];
    }
    public function mount($comunaId){

        $this->comuna['id'] = $comunaId;
        $getComunaFrete = new GetComunaFrete(new DatabaseRepositoryFactory());
        $comuna = $getComunaFrete->execute($comunaId);
        $this->comuna['designacao'] = $comuna->designacao;
        $this->comuna['provinciaId'] = $comuna->municipio->provincia->id;
        $this->comuna['municipioId'] = $comuna->municipio->id;
        $this->comuna['statusId'] = $comuna->statusId;
        $this->comuna['valorEntrega'] = $comuna->valor_entrega;

        $getProvincias = new GetProvincias(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
        $this->provincias = $getProvincias->execute();


    }
    public function render(){

        $getMunicipios = new GetMunicipiosFretePelaProvincia(new DatabaseRepositoryFactory());
        $this->municipios = $getMunicipios->execute($this->comuna['provinciaId']);
        $getComunas = new GetComunasFretePeloMunicipio(new DatabaseRepositoryFactory());
        $this->comunas = $getComunas->execute($this->comuna['municipioId']);
        return view('empresa.comunasFrete.update');
    }
    public function updateFrete(){
        $this->validate($this->rules(), $this->messages());
        try {
            DB::beginTransaction();
            $atualizarComunaFrete = new AtualizarComunaFrete(new DatabaseRepositoryFactory());
            $output = $atualizarComunaFrete->execute(new Request($this->comuna), $this->comuna['id']);
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
