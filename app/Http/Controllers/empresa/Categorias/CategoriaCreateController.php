<?php

namespace App\Http\Controllers\empresa\Categorias;
use App\Application\UseCase\Empresa\Categorias\CadastrarCategoria;
use App\Application\UseCase\Empresa\Categorias\GetCategorias;
use App\Application\UseCase\Empresa\Categorias\GetCategoriasMaeESubs;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Traits\TraitRuleUnique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoriaCreateController extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    use TraitRuleUnique;

    public $categoria;
    private $categoriaRepository;
    public $categorias;

    protected $listeners = ['selectedItem'];

    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->categoria[$item['atributo']] = $item['valor'];
    }

    public function mount(){
        $this->setarValorPadrao();
        $getCategorias = new GetCategoriasMaeESubs(new DatabaseRepositoryFactory());
        $this->categorias = $getCategorias->execute();
    }

    public function render()
    {
        return view('empresa.categorias.create');
    }

    public function salvarCategoria()
    {
        $rules = [
            'categoria.designacao' => ['required',function ($attr, $designacao, $fail) {
                $unique = TraitRuleUnique::unique('categorias', 'designacao', $designacao, null);
                if ($unique) $fail('Categoria já cadastrado');
            }],
            'categoria.status_id' => ['required']
        ];
        $messages = [
            'categoria.designacao.required' => 'Informe a categoria',
            'categoria.status_id.required' => 'Informe o status',

        ];
        $this->validate($rules, $messages);
        try {
            DB::beginTransaction();
            $cadastrarCategoria = new CadastrarCategoria(new DatabaseRepositoryFactory());
            $output = $cadastrarCategoria->execute(new Request($this->categoria));
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

    public function setarValorPadrao()
    {
        $this->categoria['designacao'] = NULL;
        $this->categoria['categoria_pai'] = NULL;
        $this->categoria['status_id'] = 1;
    }




}
