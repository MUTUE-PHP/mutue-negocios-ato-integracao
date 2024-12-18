<?php

namespace App\Http\Controllers\empresa\mercadorias;

use App\Application\UseCase\Empresa\mercadorias\CadastrarTipoMercadoria;
use App\Application\UseCase\Empresa\mercadorias\GetTiposMercadorias;
use App\Empresa\EspecificacaoMercadoria;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\TipoMercadoria;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Http\Request;

class EspecificacaoMercadoriaController extends Component
{
    use LivewireAlert;
    use WithPagination;


    protected $paginationTheme ="bootstrap";

    public $search = null;
    public $mercadoria_id;
    public $especificacao_id;
    public $mercadoria = [
        // 'statuId' => 1
    ];
    public $especificacao = [
         'status' => 1
    ];
    public function hydrate()
    {
        $this->emit('select2');
    }
    protected $listeners = [
        'selectedItem'
    ];
    public function updatingSearch(){
        $this->resetPage();
    }
    public function selectedItem($item)
    {
        $this->especificacao[$item['atributo']] = $item['valor'];
    }
    public $tiposMercadorias;
    public $countespecificacaoMercadorias;
    public function mount()
    {
        $this->countespecificacaoMercadorias = EspecificacaoMercadoria::all();

    }
    public function render()
    {


        $data['especificacaoMercadorias'] = EspecificacaoMercadoria::all();
        // $this->especificacaoMercadorias = $especificacaoMercadorias;
        return view('empresa.mercadorias.EspecificacaoMercadoria', $data);
    }
    public function store(){
        $rules = [
            'especificacao.designacao' => ['required'],
            'especificacao.desconto' => ['required'],
            'especificacao.status' => ['required']
        ];
        $messages = [
            'especificacao.designacao.required' => 'É obrigatório a descricao',
            'especificacao.desconto.required' => 'É obrigatório o desconto',
            'especificacao.status.required' => 'É obrigatório o status'
        ];
        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            // $cadastrarTipoMercadoria = new CadastrarTipoMercadoria(new DatabaseRepositoryFactory());
            // $cadastrarTipoMercadoria->execute(new Request($this->mercadoria));
            EspecificacaoMercadoria::updateOrcreate(
                ["id" => $this->especificacao_id ],
                [
                "designacao" => $this->especificacao['designacao'],
                "desconto" => $this->especificacao['desconto'],
                "status" => $this->especificacao['status']
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


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
        return redirect()->back();
    }
    public function resetField(){
        $this->especificacao_id = null;
        $this->especificacao['designacao'] = NULL;
        $this->especificacao['desconto'] = 0;
        $this->especificacao['status'] = 1;
    }

    public function edit($id)
    {
       $especificacao = EspecificacaoMercadoria::find($id);
       $this->especificacao_id  = $especificacao->id;
        $this->especificacao['designacao']  = $especificacao->designacao;
        $this->especificacao['desconto'] = $especificacao->desconto;
        $this->especificacao['status'] = $especificacao->status;
    }
    public function imprimirEspecificacao()
    {

        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "especificacaoMercadoria";

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
