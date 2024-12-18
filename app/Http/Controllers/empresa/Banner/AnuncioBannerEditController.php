<?php

namespace App\Http\Controllers\empresa\Banner;

use App\Models\admin\StatuGerais;
use App\Models\empresa\Banner;
use App\Repositories\Empresa\ProdutoDestaqueRepository;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use RealRashid\SweetAlert\Facades\Alert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AnuncioBannerEditController extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $banner;
    public $nome;
    public $descricao;
    public $imagens;
    public $imagemNovo;
    public $status_id;
    protected $listeners = ['selectedItem'];

    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->{$item['atributo']} = $item['valor'];
    }

    public function mount($id)
    {
        $this->banner = Banner::find($id);
        if (!$this->banner) {
            return redirect()->back()->with('error', 'Banner não encontrado.');
        }

        $this->nome = $this->banner->nome;
        $this->descricao = $this->banner->descricao;
        $this->status_id = $this->banner->status_id;
        $this->imagens = $this->banner->imagens;
    }

    public function updateBanner()
    {
        $mensagem = [
            'nome.required' => 'Informe nome do banner',
            'descricao.required' => 'Informe a descrição',
            'imagemNovo.mimes' => 'formato suportado (jpg,png,jpeg)'
        ];
        $rules = [
            'nome' => 'required',
            'descricao' => 'required',
            'imagemNovo' => [function ($attr, $imagem, $fail) {
                if ($imagem) {
                    if (!in_array($imagem->extension(), array("jpeg", "png", "jpg"))) {
                        $fail("Formato imagens suportado(jpeg,png,jpg)");
                    }
                }
            }],

        ];
        $this->validate($rules, $mensagem);

        $this->banner->nome = $this->nome;
        $this->banner->descricao = $this->descricao;
        $this->banner->status_id = $this->status_id;
        if($this->imagemNovo){
            if (file_exists($this->imagens)) {
                $path = public_path($this->imagens);
                unlink($path);
            }
            $this->banner->imagens = "upload/" . Storage::disk('public')->putFile('banner', $this->imagemNovo);
        }
        $this->banner->save();
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);

    }


    public function render()
    {
        return view('empresa.banners.edit');
    }


}
