<?php

namespace App\Http\Controllers\empresa\Produtos;

use App\Application\UseCase\Empresa\Produtos\GetProdutoUuid;
use App\Application\UseCase\Empresa\Produtos\GetProdutoUuidDescricao;
use App\Application\UseCase\Empresa\Produtos\GetValorCarateristica;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ProdutoDescricaoController extends Component
{
    use LivewireAlert;

    public $produtoCarateristica;
    public $produtoCarateristicaItem;

    public $modal = true;
    public $carateristicaId;
    public $produtoId;
    public $novoCarateristicaProduto;

    protected $listeners = ['deletarCarateristica'];


    public $editarDetalhe = [
        'id' => null,
        'designacao' => null,
        'caracteristicas' => []
    ];
    public $uuid;

    public function mount($uuid)
    {
        $this->uuid = $uuid;
    }
    public function novaCarateristica(){

    //    $existeCarateristica = DB::connection('mysql2')->table('categoriacaracteristicas')
    //    ->where('designacao',$this->novoCarateristicaProduto)
    //    ->first();
    //    if($existeCarateristica){
    //        $this->confirm('Carateristica já cadastrado', [
    //            'showConfirmButton' => false,
    //            'showCancelButton' => false,
    //            'icon' => 'warning'
    //        ]);
    //        return;
    //    }

        $id = DB::connection('mysql2')->table('categoriacaracteristicas')->insertGetId([
            'designacao' => $this->novoCarateristicaProduto
        ]);
        DB::connection('mysql2')->table('valorcaracteristicas_produtos')->insert([
            'valor_caracteristica_id' => $id,
            'produto_id' => $this->produtoId
        ]);
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('closeModal');
    }
    public function adicionarItemCarateristica($carateristicaId){


        if(!$this->produtoCarateristicaItem) return;
        $existeValorCarateristica = DB::connection('mysql2')->table('valorcaracteristicas')
            ->where('designacao',$this->produtoCarateristicaItem)
         ->where('categoria_caracteristica_id', $carateristicaId)->first();

        if($existeValorCarateristica){
            $this->confirm('Item já cadastrado', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        DB::connection('mysql2')->table('valorcaracteristicas')
            ->insert([
                'designacao' => $this->produtoCarateristicaItem,
                'categoria_caracteristica_id'=>$carateristicaId
            ]);
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }
    public function eliminarItemCarateristica($carateristicaItemId){
        if(!$this->produtoCarateristica) return;

        if(count($this->editarDetalhe['caracteristicas']) <= 1){
            $this->confirm('Não possivel eliminar todos items', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        DB::connection('mysql2')->table('valorcaracteristicas')
            ->where('id', $carateristicaItemId)->delete();
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);
    }
    public function editarCarateristica($carateristicaId){

        if(!$this->produtoCarateristica) return;
        DB::connection('mysql2')->table('categoriacaracteristicas')
            ->where('id', $carateristicaId)->update([
                'designacao' => $this->produtoCarateristica
            ]);
        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);

    }
    public function modalDel($carateristicaId){

        $this->carateristicaId = $carateristicaId;
        $this->confirm('Deseja apagar o item', [
            'onConfirmed' => 'deletarCarateristica',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }
    public function deletarCarateristica(){

        DB::connection('mysql2')->table('valorcaracteristicas_produtos')
            ->where('valor_caracteristica_id', $this->carateristicaId)
            ->where('produto_id', $this->produtoId)->delete();

        DB::connection('mysql2')->table('valorcaracteristicas')
            ->where('categoria_caracteristica_id', $this->carateristicaId)->delete();

        DB::connection('mysql2')->table('categoriacaracteristicas')
            ->where('id', $this->carateristicaId)->delete();


        $this->confirm('Operação realizada com sucesso', [
            'showConfirmButton' => false,
            'showCancelButton' => false,
            'icon' => 'success'
        ]);

    }

    public function modalEditarCarateristica($carateristica)
    {
        $this->produtoCarateristicaItem = null;
        $this->editarDetalhe = $carateristica;
        $this->produtoCarateristica = $carateristica['designacao'];
//        $this->dispatchBrowserEvent('showModal', 'modalEditarCarateristica');
    }

    public function render()
    {
        $getProduto = new GetProdutoUuidDescricao(new DatabaseRepositoryFactory());
        $data['produto'] = $getProduto->execute($this->uuid);
        $this->produtoId = $data['produto']['id'];
        $this->dispatchBrowserEvent('reloadTableJquery');
        return view('empresa.produtos.descricao', $data);
    }

}
