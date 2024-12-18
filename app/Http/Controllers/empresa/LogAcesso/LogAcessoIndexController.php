<?php

namespace App\Http\Controllers\empresa\LogAcesso;

use App\Models\empresa\LogAcesso;
use Livewire\Component;

class LogAcessoIndexController extends Component
{
    public $filter = [
        'orderBy' => 'DESC',
        'dataInicial' => null,
        'dataFinal' => null,
        'search' => null
    ];
    protected $listeners = [
        'selectedItem'
    ];
    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->resetPage();
        $this->filter[$item['atributo']] = $item['valor'];
    }

    public function render(){
        $data['logsAcesso'] = LogAcesso::filter($this->filter)
            ->paginate();
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.logsAcesso.index', $data);
    }

}
