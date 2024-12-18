<?php

namespace App\Http\Controllers\empresa\ModuloAcesso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class ModuloAcessoIndexController extends Component
{
    public function render()
    {
        $data['modulos'] = DB::table('users_modulos')
            ->join('modulos', 'modulos.id', '=', 'users_modulos.modulo_id')
            ->join('users_cliente', 'users_cliente.id', '=', 'users_modulos.user_id')
            ->where('user_id', Auth::user()->id)
            ->select('modulos.id', 'users_cliente.email', 'modulos.url', 'modulos.designacao')
            ->get();
        return view("empresa.modulosAcesso.index", $data);
    }
}
