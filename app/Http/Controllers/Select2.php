<?php

namespace App\Http\Controllers;

use Livewire\Component;

class Select2 extends Component
{
    public $selected = '';
    public $serie;

    public $series = [
        'Wanda Vision',
        'Money Heist',
        'Lucifer',
        'Stranger Things',
    ];

    public function render()
    {
        return view('livewire.select2')
            ->layout('layouts.select2');
    }
}
