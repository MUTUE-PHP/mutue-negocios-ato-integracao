<?php

namespace App\Http\Controllers\empresa\Parametros;

use App\Application\UseCase\Empresa\Parametros\AtualizarParametro;
use App\Application\UseCase\Empresa\Parametros\GetParametro;
use App\Application\UseCase\Empresa\Parametros\GetParametrosEmpresaAutenticada;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ParametroIndexController extends Component
{
    use LivewireAlert;
    public $valorParametro;

    protected $listeners = ['selectedItem'];

    public function selectedItem($item)
    {
        $this->{$item['atributo']} = $item['valor'];
    }
    public function hydrate()
    {
        $this->emit('select2');
    }

    public function render()
    {
        $getParametros = new GetParametrosEmpresaAutenticada(new DatabaseRepositoryFactory());
        $parametros = $getParametros->execute();
        foreach ($parametros as $key => $parametro) {
            $selectArray = explode(",", $parametro['valorSelects']);
            $parametros[$key]['valorSelects'] = $selectArray;
        }
        $data['parametros'] = $parametros;
        return view('empresa.Parametros.create', $data);
    }

    public function atualizarParametro($parametroId)
    {
        if (!$this->valorParametro) return;
        $getParametro = new GetParametro(new DatabaseRepositoryFactory());
        $parametro = $getParametro->execute($parametroId);
        if (!$parametro) return response()->back();
        $atualizarParametro = new AtualizarParametro(new DatabaseRepositoryFactory());
        $output = $atualizarParametro->execute($parametro->id, $this->valorParametro, $parametro->label);
        if ($output) {
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
            $this->dispatchBrowserEvent('refreshPagina');
        }
    }
}
