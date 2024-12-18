<?php

namespace App\Http\Controllers\empresa\Cambio;


use App\empresa\EspecificacaoMercadoria;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Cambio;
use App\Models\empresa\TipoMercadoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class CambioController extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $paginationTheme ="bootstrap";

    public $search = null;
    public $mercadoria_id;
    public $cambio_id;
    public $cambio = [];

    public $tiposMercadorias;
    public $especificacaoMercadorias;

    public function updatingSearch(){
        $this->resetPage();
    }
    public function mount()
    {
        // $getTiposMercadorias = new GetTiposMercadorias(new DatabaseRepositoryFactory());
        // $this->tiposMercadorias = $getTiposMercadorias->execute();
    }
    public function render()
    {

        $data['cambios'] = Cambio::all();
        // $this->especificacaoMercadorias = $especificacaoMercadorias;
        return view('empresa.cambios.Cambio', $data);
    }
    public function store(){
        //  dd($this->cambio);
        $rules = [
            'cambio.designacao' => ['required'],
            'cambio.valor' => ['required'],
        ];
        $messages = [
            'cambio.designacao.required' => 'É obrigatório a designacao',
            'cambio.valor.required' => 'É obrigatório o valor',
        ];
        $this->validate($rules, $messages);

        // try {
            DB::beginTransaction();
            // $cadastrarTipoMercadoria = new CadastrarTipoMercadoria(new DatabaseRepositoryFactory());
            // $cadastrarTipoMercadoria->execute(new Request($this->mercadoria));
            Cambio::updateOrcreate(
                ["id" => $this->cambio_id ],
                [
                "designacao" => $this->cambio['designacao'],
                "valor" => $this->cambio['valor'],
            ]);
            DB::commit();
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->resetField();
            $this->mount();
            $this->dispatchBrowserEvent('reloadTableJquery');
            $this->dispatchBrowserEvent('close-modal');


        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error($e->getMessage());
        // }
        return redirect()->back();
    }
    public function resetField(){
        $this->cambio_id = null;
        $this->cambio['designacao'] = NULL;
        $this->cambio['valor'] = 0;
    }

    public function edit($id)
    {
       $cambio = Cambio::find($id);
       $this->cambio_id  = $cambio->id;
        $this->cambio['designacao']  = $cambio->designacao;
        $this->cambio['valor'] = $cambio->valor;
    }


}
