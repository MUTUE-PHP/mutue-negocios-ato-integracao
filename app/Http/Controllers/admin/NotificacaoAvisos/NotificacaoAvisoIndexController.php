<?php

namespace App\Http\Controllers\admin\NotificacaoAvisos;
use Livewire\Component;

class NotificacaoAvisoIndexController extends Component
{
    public $search;

    public function render()
    {
        $data = [];
        return view('admin.notificacaoAviso.index',$data)->layout('layouts.appAdmin');
    }

}
