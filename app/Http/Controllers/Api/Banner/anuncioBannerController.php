<?php
namespace App\Http\Controllers\Api\Banner;
use App\Http\Controllers\Controller;
use App\Models\empresa\Banner;
use App\Repositories\Empresa\ProdutoDestaqueRepository;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class anuncioBannerController extends Controller
{

    public function listarBanner()
    {
        $banner = Banner::where('status_id', 1)->get();
        return response()->json($banner);
    }

}
