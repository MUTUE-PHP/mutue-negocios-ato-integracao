<?php

namespace App\Http\Controllers\empresa\Banner;

use App\Models\admin\StatuGerais;
use App\Models\empresa\Banner;
use App\Repositories\Empresa\ProdutoDestaqueRepository;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use RealRashid\SweetAlert\Facades\Alert;
use Livewire\Component;

class AnuncioBannerCreateController extends Component
{

    use LivewireAlert;
    use WithFileUploads;


    public $nome;
    public $descricao;
    public $status_id = 1;
    public $imagens;

    protected $listeners = ['selectedItem'];

    public function hydrate()
    {
        $this->emit('select2');
    }
    public function selectedItem($item)
    {
        $this->{$item['atributo']} = $item['valor'];
    }
    public function setarValor(){
        $this->nome = null;
        $this->descricao = null;
        $this->status_id = 1;
        $this->imagens = null;

    }
    public function render()
    {
        return view('empresa.banners.create');
    }

    public function salvarNovoBanner()
    {

        $mensagem = [
            'nome.required' => 'Informe nome do banner',
            'descricao.required' => 'Informe a descrição',
            'imagens.required' => 'Informe a imagem',
            'imagens.mimes' => 'formato suportado (jpg,png,jpeg)'
        ];
        $rules = [
            'nome' => 'required',
            'descricao' => 'required',
            'imagens' => 'required|mimes:jpg,png,jpeg',

        ];
        $this->validate($rules, $mensagem);

        $banner = new Banner;
        $banner->nome = $this->nome;
        $banner->descricao = $this->descricao;
        $banner->status_id = $this->status_id;
        $banner->imagens = "upload/" . Storage::disk('public')->putFile('banner', $this->imagens);
        $banner->save();
        $this->setarValor();
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }
}
