<?php

namespace App\Http\Controllers\admin\NotificacaoAvisos;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NotificacaoAvisoCreateController extends Component
{
    use LivewireAlert;

    public $search;
    public $titulo;
    public $body;

    protected $listeners = [
        'selectedContentTinymce','salvarNotificacao'
    ];



    // public function hydrate()
    // {
    //     $this->emit('initTinymce');
    // }

    public function selectedContentTinymce($body)
    {
        $this->body = $body;
    }

    public function render()
    {
        $data = [];
        return view('admin.notificacaoAviso.create', $data)->layout('layouts.appAdmin');
    }

    public function salvarNotificacao()
    {
    $this->validate($this->rules(), $this->messages());

        // dd('teste');
        // $this->validate([
        //     'body' => 'required|string'
        // ]);

        dd($this->body);

        // if(!$this->body){
        //     $this->confirm('Informe o texto do aviso', [
        //         'showConfirmButton' => false,
        //         'showCancelButton' => false,
        //         'icon' => 'warning'
        //     ]);
        //     return;
        // }
        // return;

        // $this->validate($this->rules(), $this->messages());


        // dd($this->body);
    }
    public function rules()
    {
        return [
            'body' => "required",

        ];
    }
    public function messages()
    {
        return [
            'body.required' => 'Informe o texto da notificação'
        ];
    }
}
