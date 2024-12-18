<?php

namespace App\Http\Controllers\empresa\PermissaoCentroCusto;

use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCustoSemPaginacao;
use App\Application\UseCase\Empresa\Users\AtualizarPermissaoCentroCusto;
use App\Application\UseCase\Empresa\Users\GetUserPeloUuid;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PermissaoCentroCustoIndexController extends Component
{
    use LivewireAlert;
    public $user;
    public $idsCentrosCustoUser = [];
    public function mount($uuid){
        $getUser = new GetUserPeloUuid(new DatabaseRepositoryFactory());
        $user = $getUser->execute($uuid);
        $this->user = $user;
        foreach ($user->centrosCusto as $centroCusto) {
            array_push($this->idsCentrosCustoUser, $centroCusto['id']);
        }
        if(!$user) return response()->redirectToRoute('users.index');
    }
    public function render(){
        $getCentrosCusto = new GetCentrosCustoSemPaginacao(new DatabaseRepositoryFactory());
        $data['centrosCusto'] = $getCentrosCusto->execute();
        return view('empresa.permissaoCentroCusto.create', $data);
    }
    public function checkPermissaoPorUsuario($centroCustoId){


        $atualizarPermissaoCentroCusto = new AtualizarPermissaoCentroCusto(new DatabaseRepositoryFactory());
        $atualizarPermissaoCentroCusto->execute($centroCustoId, $this->user->id);
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success',
        ]);
    }
}
