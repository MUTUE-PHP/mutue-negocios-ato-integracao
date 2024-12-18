<?php

namespace App\Http\Controllers\empresa\Taxas;



use App\Models\empresa\Cambio;
use App\Models\empresa\IntervaloPmd;
use App\Models\empresa\TipoMercadoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Http\Request;

class IntervaloPmdController extends Component
{
    use LivewireAlert;

    public $search = null;

    public $modalTitle;

    public $pmds;
    public $pmd = [];
    public $pmd_id;

    public function mount()
    {
        $this->modalTitle = " NOVO INTERVALO PMD";
        // $getTiposMercadorias = new GetTiposMercadorias(new DatabaseRepositoryFactory());
        // $this->tiposMercadorias = $getTiposMercadorias->execute();
    }
    public function render()
    {
        
        $this->pmds = IntervaloPmd::all();
        return view('empresa.Taxas.IntervaloPmd', compact($this->pmds));
    }
    public function store(){
        //  dd($this->cambio);
        $rules = [
            'pmd.toneladas_max' => ['required'],
            'pmd.toneladas_min' => ['required'],
            'pmd.taxa' => ['required'],
        ];
        $messages = [
            'pmd.toneladas_max.required' => 'É obrigatório a toneladas_max',
            'pmd.toneladas_min.required' => 'É obrigatório a toneladas_min',
            'pmd.taxa.required' => 'É obrigatório a taxa',

        ];
        $this->validate($rules, $messages);

        // try {
            DB::beginTransaction();

            IntervaloPmd::updateOrcreate(
                ["id" => $this->pmd_id],
                [
                "toneladas_max" => $this->pmd['toneladas_max'],
                "toneladas_min" => $this->pmd['toneladas_min'],
                "taxa" => $this->pmd['taxa'],
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
        $this->modalTitle = " NOVO INTERVALO PMD";
         $this->pmd_id = null;
         $this->pmd['toneladas_max'] = NULL;
         $this->pmd['toneladas_min'] = NULL;
         $this->pmd['taxa'] = 0;

    }

    public function edit($id)
    {
     $this->modalTitle = " EDITAR INTERVALO PMD";
       $pmd = IntervaloPmd::find($id);
       $this->pmd_id  = $pmd->id;
        $this->pmd['toneladas_max']  = $pmd->toneladas_max;
        $this->pmd['toneladas_min'] = $pmd->toneladas_min;
        $this->pmd['taxa'] = $pmd->taxa;

    }


}