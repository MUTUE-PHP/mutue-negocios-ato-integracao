<?php

namespace App\Http\Controllers\admin\Users;

use App\Repositories\Admin\UserRepository;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserCreateController extends Component
{

    use WithFileUploads;
    use LivewireAlert;

    private $userRepository;
    public $utilizador;



    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->setarValorInicial();
    }

    public function render()
    {
        $data['perfis'] = DB::connection('mysql')->table('perfils')->get();
        return view('admin.usuarios.create', $data)->layout('layouts.appAdmin');
    }
    public function salvarUtilizador()
    {
        $rules = [
            'utilizador.name' => 'required|min:3|max:255',
            'utilizador.role_id' => 'required',
            'utilizador.username' => 'required|min:3|max:255',
            // 'email' => 'required|exists:mysql.mutue_negocios_admin.users_admin,email',
            'utilizador.email' => 'required|unique:mysql.users_admin,email|email|min:3',
            'utilizador.telefone' => 'required|unique:mysql.users_admin,telefone',
            'utilizador.foto' => ''
        ];
        $messages = [
            'utilizador.name.required' => 'Nome é obrigatorio',
            'utilizador.role_id.required' => 'Informe o perfil do utilizador',
            'utilizador.username.required' => 'Username é obrigatorio',
            'utilizador.email.required' => 'E-mail é obrigatorio',
            'utilizador.telefone.required' => 'Telefone é obrigatorio',
            'utilizador.email.unique' => 'E-mail já cadastrado',
            'utilizador.telefone.unique' => 'Telefone já cadastrado',
        ];

        $this->validate($rules, $messages);
        $this->userRepository->createNewUser($this->utilizador);
        $this->alert('success', 'Operação realizada com sucesso');
        $this->setarValorInicial();
    }
    public function setarValorInicial()
    {

        $this->utilizador['status_id'] = 1;
        $this->utilizador['name'] = NULL;
        $this->utilizador['username'] = NULL;
        $this->utilizador['email'] = NULL;
        $this->utilizador['role_id'] = NULL;
        $this->utilizador['telefone'] = NULL;
        $this->utilizador['foto'] = NULL;
    }
}
