<?php

namespace App\Http\Controllers\empresa\Categorias;
use App\Application\UseCase\Empresa\Categorias\AtualizarCategoria;
use App\Application\UseCase\Empresa\Categorias\CadastrarCategoria;
use App\Application\UseCase\Empresa\Categorias\GetCategoria;
use App\Application\UseCase\Empresa\Categorias\GetCategorias;
use App\Application\UseCase\Empresa\Categorias\GetCategoriasMaeESubs;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Traits\TraitRuleUnique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CategoriaUpdateController extends Component
{
    use LivewireAlert;
    public $categoria;
    public $categorias;
    public $categoriaId;

    protected $listeners = ['selectedItem'];

    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->categoria[$item['atributo']] = $item['valor'];
    }



    public function mount($categoriaId)
    {
        $this->categoriaId = $categoriaId;
        $getCategorias = new GetCategoriasMaeESubs(new DatabaseRepositoryFactory());
        $this->categorias = $getCategorias->execute();

        $getCategoria = new GetCategoria(new DatabaseRepositoryFactory());
        $categoria = $getCategoria->execute($categoriaId);

        if(!$categoria) return $this->redirectRoute('categorias.index');
        $this->categoria['categoria_pai'] = $categoria['categoria_pai'];
        $this->categoria['designacao'] = $categoria['designacao'];
        $this->categoria['status_id'] = $categoria['status_id'];
    }


    public function render()
    {
        return view('empresa.categorias.edit');
    }
    public function update()
    {
        $rules = [
            'categoria.designacao' => ['required',function ($attr, $designacao, $fail)  {
                $unique = TraitRuleUnique::unique('categorias', 'designacao', $designacao, $this->categoriaId);
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
            $atualizarCategoria = new AtualizarCategoria(new DatabaseRepositoryFactory());
            $output = $atualizarCategoria->execute(new Request($this->categoria), $this->categoriaId);
            DB::commit();
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

    }
}
