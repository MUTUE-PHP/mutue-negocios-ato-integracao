<?php

namespace App\Http\Controllers\admin\Users;
use App\Application\UseCase\Admin\users\GetPermissoesSemPaginacao;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use App\Models\admin\User;
use App\Models\admin\UserPermissions;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AdminUserPermissoesIndexController extends Component
{
    use LivewireAlert;

    public $user;
    public $selectedPermissoes = [];
    public $permissoes;
    public function mount($id){

        $user = User::where('id', $id)->first();
        if (!$user) {
            return redirect()->route('AdminUserIndex');
        }
        $this->user = $user;

        $getPermissoes = new GetPermissoesSemPaginacao(new DatabaseRepositoryFactory());
        $this->permissoes = $getPermissoes->execute();
        $this->selectedPermissoes = [];
        $userPermissoes = DB::connection('mysql')->table('model_has_permissions')
            ->where('model_id', $this->user->id)->get();
        foreach ($userPermissoes as $user) {
            array_push($this->selectedPermissoes, $user->permission_id);
        }

    }
    public function render(){
        return view('admin.usuarios.permissoes')->layout('layouts.appAdmin');
    }
    public function checkPermissaoPorUsuario($permissaoId)
    {
        $permissao = DB::connection('mysql')->table('model_has_permissions')
        ->where('model_id', $this->user->id)
            ->where('permission_id', $permissaoId)
            ->first();
        if ($permissao) {
            DB::connection('mysql')->table('model_has_permissions')->where('model_id', $this->user->id)
                ->where('permission_id', $permissaoId)->delete();
        } else {
            DB::connection('mysql')->table('model_has_permissions')->insert([
                'permission_id' => $permissaoId,
                'model_type' => 'App\Models\empresa\User',
                'model_id' => $this->user->id
            ]);
        }
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success',
        ]);
    }

}
