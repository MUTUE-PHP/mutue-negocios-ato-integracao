<?php

namespace App\Http\Controllers\empresa\mercadorias;

use App\Application\UseCase\Empresa\mercadorias\CadastrarTipoMercadoria;
use App\Application\UseCase\Empresa\mercadorias\GetTiposMercadorias;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\TipoMercadoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Http\Request;

class MercadoriaIndexController extends Component
{
    use LivewireAlert;

    public $search = null;
    public $mercadoria = [
        'statuId' => 1
    ];
    public $tiposMercadorias;

    public function mount()
    {
        $getTiposMercadorias = new GetTiposMercadorias(new DatabaseRepositoryFactory());
        $this->tiposMercadorias = $getTiposMercadorias->execute();
    }
    public function render()
    {
        return view('empresa.mercadorias.index');
    }
    public function salvarTipoMercadoria(){

        $rules = [
            'mercadoria.designacao' => ['required'],
            'mercadoria.taxa' => ['required'],
            'mercadoria.statuId' => ['required']
        ];
        $messages = [
            'mercadoria.designacao.required' => 'É obrigatório o nome',
            'mercadoria.valor.required' => 'É obrigatório o preço',
            'mercadoria.statuId.required' => 'É obrigatório o status'
        ];
        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $cadastrarTipoMercadoria = new CadastrarTipoMercadoria(new DatabaseRepositoryFactory());
            $cadastrarTipoMercadoria->execute(new Request($this->mercadoria));
            DB::commit();
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->resetField();
            $this->mount();
            $this->dispatchBrowserEvent('reloadTableJquery');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
    public function resetField(){
        $this->mercadoria['designacao'] = NULL;
        $this->mercadoria['taxa'] = 0;
        $this->mercadoria['statuId'] = 1;
    }

    public function edit($id)
    {
       $tipoMercadoria = TipoMercadoria::find($id);
       $this->mercadoria['id']  = $tipoMercadoria->id;
        $this->mercadoria['designacao']  = $tipoMercadoria->designacao;
        $this->mercadoria['taxa'] = $tipoMercadoria->taxa;
        $this->mercadoria['statuId'] = $tipoMercadoria->statuId;
    }

    public function update()
    {
        $rules = [
            'mercadoria.designacao' => ['required'],
            'mercadoria.taxa' => ['required'],
            'mercadoria.statuId' => ['required']
        ];
        $messages = [
            'mercadoria.designacao.required' => 'É obrigatório o nome',
            'mercadoria.taxa.required' => 'É obrigatório o preço',
            'mercadoria.statuId.required' => 'É obrigatório o status'
        ];

        $this->validate($rules, $messages);

        TipoMercadoria::updateOrcreate(
            ['id' => $this->mercadoria['id'] ],
            [
                'designacao' => $this->mercadoria['designacao'],
                'taxa' => $this->mercadoria['taxa'],
                'statuId' => $this->mercadoria['statuId']

            ]);

            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);

//            $this->resetField();
            $this->mount();
            $this->dispatchBrowserEvent('reloadTableJquery');

    }
    public function imprimirTiposMercadorias()
    {

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "tiposMercadorias";

        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'logotipo' => $logotipo,
                ]

            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }

}
