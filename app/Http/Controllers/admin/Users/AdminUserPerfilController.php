<?php

namespace App\Http\Controllers\admin\Users;

use App\Application\UseCase\Admin\users\GetPerfisUtilizadores;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Livewire\Component;

class AdminUserPerfilController extends Component
{
    public function render(){
        $getRoles = new GetPerfisUtilizadores(new DatabaseRepositoryFactory());
        $data['perfis'] = $getRoles->execute();
        return view('admin.usuarios.perfil', $data)->layout('layouts.appAdmin');
    }
}
