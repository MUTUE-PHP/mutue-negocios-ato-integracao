<?php

namespace App\Http\Controllers\admin;
use Livewire\Component;

class AdminSemPermissaoController extends Component
{
    public function render()
    {
        return view('admin.permissoes.notPermission')->layout('layouts.appAdmin');
    }

}
