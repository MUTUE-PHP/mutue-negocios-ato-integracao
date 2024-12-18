<?php

namespace App\Http\Controllers\empresa\Banner;

use App\Models\empresa\Banner;
use App\Repositories\Empresa\ProdutoDestaqueRepository;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AnuncioBannerIndexController extends Component
{

    use LivewireAlert;
    public function render()
    {
        $banner = Banner::all();
        return view('empresa.banners.index', compact('banner'));
    }
}
