<?php

namespace App\Http\Controllers\admin\Users;

use App\Application\UseCase\Admin\users\CreatePerfil;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AdminUserPerfilCreateController extends Component
{
    use LivewireAlert;

    public $role;

    public function render(){
        return view('admin.usuarios.perfilNovo')->layout('layouts.appAdmin');
    }
    public function mount(){
        $this->setarValorPadrao();
    }
    public function setarValorPadrao()
    {
        $this->role['status_id'] = 1;
        $this->role['designacao'] = NULL;
    }
    public function salvarPerfil(){

        $rules = [
            'role.designacao' => ["required", function ($attr, $value, $fail) {
                $role = DB::connection('mysql')->table('perfils')
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
        $createPerfil = new CreatePerfil(new DatabaseRepositoryFactory());
        $createPerfil->execute($this->role);
        $this->setarValorPadrao();

        $this->confirm('Operação Realizada com Sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success',
        ]);

    }

}
