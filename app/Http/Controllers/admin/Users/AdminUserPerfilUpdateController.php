<?php

namespace App\Http\Controllers\admin\Users;

use App\Application\UseCase\Admin\users\CreatePerfil;
use App\Application\UseCase\Admin\users\GetPerfilUuid;
use App\Application\UseCase\Admin\users\UpdatePerfil;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AdminUserPerfilUpdateController extends Component
{
    use LivewireAlert;

    public $role;

    public function render(){
        return view('admin.usuarios.perfilUpdate')->layout('layouts.appAdmin');
    }
    public function mount($uuid){

        $getPerfil = new GetPerfilUuid(new DatabaseRepositoryFactory());
        $perfil = $getPerfil->execute($uuid);
        $this->role['id'] = $perfil['id'];
        $this->role['designacao'] = $perfil['designacao'];
        $this->role['status_id'] = $perfil['status_id'];
    }

    public function salvarPerfil(){

        $rules = [
            'role.designacao' => ["required", function ($attr, $value, $fail) {
                $role = DB::connection('mysql')->table('perfils')
                    ->where('id', '!=', $this->role['id'])
                    ->where('designacao', $value)->first();
                if ($role) {
                    $fail("Função já cadastrado");
                }
            }],
            "role.status_id" => "required"

        ];
        $messages = [
            'role.designacao.required' => 'Informe a função',
            'role.status_id.required' => 'Informe o status',
        ];

        $this->validate($rules, $messages);
        $createPerfil = new UpdatePerfil(new DatabaseRepositoryFactory());
        $createPerfil->execute($this->role);

        $this->confirm('Operação Realizada com Sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success',
        ]);

    }

}
