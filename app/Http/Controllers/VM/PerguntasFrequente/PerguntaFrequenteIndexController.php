<?php

namespace App\Http\Controllers\VM\PerguntasFrequente;

use App\Application\UseCase\VendasOnline\PerguntasFrequentes\AtualizarPerguntasFrequentes;
use App\Application\UseCase\VendasOnline\PerguntasFrequentes\CadastrarPerguntasFrequentes;
use App\Application\UseCase\VendasOnline\PerguntasFrequentes\EliminarPerguntaFrequente;
use App\Application\UseCase\VendasOnline\PerguntasFrequentes\GetPerguntasFrequentes;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PerguntaFrequenteIndexController extends Component
{

    use LivewireAlert;

    public $pergunta;
    public $resposta;
    public $perguntaFrequenteId;
    public $perguntaEdit;
    public $respostaEdit;
    public $search = null;
    protected $listeners = ['deletarPerguntaFrequente'];


    public function render()
    {
        $getPerguntasFrequentes = new GetPerguntasFrequentes(new DatabaseRepositoryFactory());
        $perguntasFrequentes = $getPerguntasFrequentes->execute($this->search);
        $data['perguntasFrequentes'] = $perguntasFrequentes;
        $this->dispatchBrowserEvent('reloadTableJquery');

        return view('empresa.perguntasFrequente.index', $data);
    }

    public function getPerguntasFrequentes()
    {
        $getPerguntasFrequentes = new GetPerguntasFrequentes(new DatabaseRepositoryFactory());
        return $getPerguntasFrequentes->execute();
    }

    public function atualizarPerguntaFrequentes()
    {
        $request = new Request([
            'id' => $this->perguntaFrequenteId,
            'perguntaEdit' => $this->perguntaEdit,
            'respostaEdit' => $this->respostaEdit
        ]);
        $messages = [
            'perguntaEdit.required' => 'Informe a pergunta',
            'respostaEdit.required' => 'Informe a resposta',
        ];
        $rules = [
            'perguntaEdit' => ['required'],
            'respostaEdit' => ['required'],
        ];
        $this->validate($rules, $messages);
        $cadastrarPerguntaFrequente = new AtualizarPerguntasFrequentes(new DatabaseRepositoryFactory());
        $output = $cadastrarPerguntaFrequente->execute($request);
        if ($output) {
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        }
    }

    public function cadastrarPerguntaFrequentes()
    {

        $request = new Request([
            'pergunta' => $this->pergunta,
            'resposta' => $this->resposta
        ]);

        $messages = [
            'pergunta.required' => 'Informe a pergunta',
            'resposta.required' => 'Informe a resposta',
        ];

        $rules = [
            'pergunta' => ['required'],
            'resposta' => ['required'],
        ];
        $this->validate($rules, $messages);
        $cadastrarPerguntaFrequente = new CadastrarPerguntasFrequentes(new DatabaseRepositoryFactory());
        $output = $cadastrarPerguntaFrequente->execute($request);
        $this->pergunta = null;
        $this->resposta = null;
        if ($output) {
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
        }
    }

    public function modalDel($perguntaFrequenteId)
    {
        $this->perguntaFrequenteId = $perguntaFrequenteId;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'deletarPerguntaFrequente',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }

    public function deletarPerguntaFrequente($data)
    {
        if ($data['value']) {
            $eliminarPerguntaFrequente = new EliminarPerguntaFrequente(new DatabaseRepositoryFactory());
            $eliminarPerguntaFrequente->execute($this->perguntaFrequenteId);
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);

        }
    }

    public function showModalUpdatePerguntaFrequentes($data)
    {
        $this->perguntaFrequenteId = $data['id'];
        $this->perguntaEdit = $data['pergunta'];
        $this->respostaEdit = $data['resposta'];
    }
}
