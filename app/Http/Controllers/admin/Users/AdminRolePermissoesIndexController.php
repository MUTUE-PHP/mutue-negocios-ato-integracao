<?php

namespace App\Http\Controllers\admin\Users;

use App\Application\UseCase\Admin\users\CheckPermissaoPorRole;
use App\Application\UseCase\Admin\users\GetPerfilUuid;
use App\Application\UseCase\Admin\users\GetPermissoes;
use App\Application\UseCase\Admin\users\GetPermissoesPorRole;
use App\Application\UseCase\Admin\users\GetPermissoesSemPaginacao;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AdminRolePermissoesIndexController extends Component
{
    public $permissoes;
    public $role;
    public $selectedPermissoes = [];
    use LivewireAlert;

    public function mount($uuid)
    {
        $getPerfils = new GetPerfilUuid(new DatabaseRepositoryFactory());
        $perfil = $getPerfils->execute($uuid);
        if (!$perfil) {
            return redirect()->route('admin.users.funcao');
        }
        $this->role = $perfil;

        $getPermissoes = new GetPermissoesSemPaginacao(new DatabaseRepositoryFactory());
        $this->permissoes = $getPermissoes->execute();
        $this->selectedPermissoes = [];
        $getPermissoesPorRole = new GetPermissoesPorRole(new DatabaseRepositoryFactory());
        $rolePermissoes = $getPermissoesPorRole->execute($this->role->id);
        foreach ($rolePermissoes as $perfil) {
            array_push($this->selectedPermissoes, $perfil['permission_id']);
        }
    }

    public function render(){
        return view('admin.usuarios.rolesPermissoes')->layout('layouts.appAdmin');
    }
    public function checkPermissaoPorRole($permissaoId)
    {
        $checkPermissaoPorRole = new CheckPermissaoPorRole(new DatabaseRepositoryFactory());
        $checkPermissaoPorRole->execute($permissaoId, $this->role->id);

        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success',
        ]);
    }

}
