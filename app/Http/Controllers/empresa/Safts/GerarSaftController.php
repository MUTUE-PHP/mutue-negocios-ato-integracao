<?php

namespace App\Http\Controllers\empresa\Safts;

use App\Application\UseCase\Empresa\Saft\GeradorDoFicheiroSaft;
use App\Http\Controllers\TraitLogAcesso;
use Livewire\Component;


class GerarSaftController extends Component
{
    use TraitLogAcesso;
    public $mes;
    public function render()
    {
        return view('empresa.gerarSaft.index');
    }
    public function printSaft()
    {
        $rules = [
            'mes' => 'required',
        ];
        $messages = [
            'mes.required' => 'campo obrigatório'
        ];
        $this->validate($rules, $messages);
        $this->logAcesso();
        $gerarSaft = new GeradorDoFicheiroSaft();
        return ($gerarSaft->execute($this->mes));
    }


}
