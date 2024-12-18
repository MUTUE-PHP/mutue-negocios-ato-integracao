<?php

namespace App\Http\Controllers\empresa\Usuarios;

use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Empresa\RoleRepository;
use App\Repositories\Empresa\UserRepository;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class UsuarioUpdateController extends Component
{

    use LivewireAlert;
    use UpdateUserRequest;
    use WithFileUploads;


    public $user;
    public $userId;
    public $newFoto;
    public $selectedPerfils = [];
    private $userRepository;
    private $roleRepository;

    protected $listeners = [
        'selectedFuncaoItem',
    ];

    public function selectedFuncaoItem($item)
    {

        $this->selectedPerfils = $item;
        if(!$this->selectedPerfils){
            $this->selectedPerfils = [];
        }

    }
    public function hydrate()
    {
        $this->emit('select100');
    }

    public function boot(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function mount($uuid)
    {
        $this->userId = $uuid;
        $this->user = $this->userRepository->getUser($uuid);

        $this->selectedPerfils = [];
        foreach ($this->user['perfis'] as $perfil) {
            array_push($this->selectedPerfils, $perfil['id']);
        }
        $this->roles = $this->roleRepository->listarPerfis();

        if (!$this->user) return redirect()->back();
    }


    public function render()
    {
        return view('empresa.usuarios.edit');
    }
    public function atualizarUsuario()
    {

        if (!$this->user['name']) {
            $this->confirm('Informe o nome', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        if (!$this->user['email']) {
            $this->confirm('Informe o email', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        } else {
            $user =  DB::table('users_cliente')
                ->where('empresa_id', auth()->user()->empresa_id)
                ->where('email', $this->user['email'])
                ->where('id','!=', $this->user['id'])
                ->first();

            if ($user) {
                $this->confirm('E-mail já cadastrado', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
        }
        if (!$this->user['telefone']) {
            $this->confirm('Informe o telefone', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $this->user['newFoto'] = $this->newFoto;
        if (count($this->selectedPerfils) <= 0) {
            $this->confirm('Informe pelo menos uma função', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        // dd($this->selectedPerfils);

        // $this->validate($this->rules(), $this->messages());
        $this->userRepository->updateUser($this->user, $this->selectedPerfils);
        // $this->mount($this->user->uuid);

        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }
}
