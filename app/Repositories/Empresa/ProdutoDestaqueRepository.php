<?php

namespace App\Repositories\Empresa;

use App\Models\empresa\Produto;
use App\Models\empresa\ProdutoDestaque;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdutoDestaqueRepository
{

    use TraitConstrutorCarateristica;

    protected $produtoDestaque;
    protected $produto;

    public function __construct(ProdutoDestaque $produtoDestaque, Produto $produto)
    {
        $this->produtoDestaque = $produtoDestaque;
        $this->produto = $produto;
    }
    public function listarProdutoDestaqueAtualizar(){
        $produtos = $this->produto::where('venda_online', 'Y')
            ->where('empresa_id', auth()->user()->empresa_id)->get();
        return $produtos;
    }
    public function listarProdutoVendasOnline()
    {

        $produtoDestaqueIds = $this->produtoDestaque::pluck('produto_id')->toArray();
        $produtos = $this->produto::where('venda_online', 'Y')
            ->whereNotIn('id', $produtoDestaqueIds)
            ->where('empresa_id', auth()->user()->empresa_id)->get();
        return $produtos;
    }
    public function getProdutos($request, $search)
    {
        $produtoDestaques = $this->produtoDestaque::with([
            'produto',
            'produto.existenciaEstock',
            'produto.valorCaracteristica',
            'produto.valorCaracteristica',
            'produto.produtoImagens',
            'produto.categoria',
            'produto.status',
            'produto.empresa'
        ])
            ->search(trim($search))
            ->orderByFilter($request->orderBy)
            ->paginate();

        foreach ($produtoDestaques as $key => $produtoDestaque) {
            $produtoDestaques[$key]['caracteristicas'] =  $this->construirCaracteristicas($produtoDestaque->produto);
            $produtoDestaques[$key]['classificacao'] = [
                [
                    'classificacao' => 1,
                    'users' => $this->countUsers(1, $produtoDestaque->produto['id']),
                    'percentagem' => $this->calcularPercentagem(1, $produtoDestaque->produto['id'])
                ], [
                    'classificacao' => 2,
                    'users' => $this->countUsers(2, $produtoDestaque->produto['id']),
                    'percentagem' => $this->calcularPercentagem(2, $produtoDestaque->produto['id'])
                ],
                [
                    'classificacao' => 3,
                    'users' => $this->countUsers(3, $produtoDestaque->produto['id']),
                    'percentagem' => $this->calcularPercentagem(3, $produtoDestaque->produto['id'])

                ],
                [
                    'classificacao' => 4,
                    'users' => $this->countUsers(4, $produtoDestaque->produto['id']),
                    'percentagem' => $this->calcularPercentagem(4, $produtoDestaque->produto['id'])

                ],
                [
                    'classificacao' => 5,
                    'users' => $this->countUsers(5, $produtoDestaque->produto['id']),
                    'percentagem' => $this->calcularPercentagem(5, $produtoDestaque->produto['id'])

                ]
            ];
            $produtoDestaques[$key]['totalClassificacao'] = 0;
            $subClassificado = 0;
            $users = 0;
            foreach ($produtoDestaques[$key]['classificacao'] as $subtotal) {
                $subClassificado += $subtotal['classificacao'] * $subtotal['users'];
                $users += $subtotal['users'];
            }
            if ($subClassificado == 0 || $users == 0) {
                $produtoDestaques[$key]['totalClassificacao'] = 0;
            } else {
                $produtoDestaques[$key]['totalClassificacao'] = $subClassificado / $users;
            }

        }
        return $produtoDestaques;
    }
    public function countUsers($classificacao, $produtoId)
    {
        return DB::connection('mysql2')->table('classificacao')
            ->where('produto_id', $produtoId)
            ->where('num_classificacao', $classificacao)
            ->count();
    }
    public function calcularPercentagem($classificacao, $produtoId)
    {
        $totalUsers = $this->totalUsers($produtoId);
        if ($totalUsers <= 0) return 0;
        return (($classificacao * $this->countUsers($classificacao, $produtoId)) / $totalUsers) / 5;
    }
    public function totalUsers($produtoId)
    {
        return DB::connection('mysql2')->table('classificacao')
            ->where('produto_id', $produtoId)
            ->count();
    }

    public function getDestaque($uuid){

        $destaque = $this->produtoDestaque::where('uuid', $uuid)->with(['produto'])->first();
        return $destaque;

    }
    public function adicionarProdutoDestaque($destaque)
    {

        return $this->produtoDestaque::create([
            'uuid' => (string) Str::uuid(),
            'produto_id' => $destaque['produtoId'],
            'designacao' => $destaque['designacao'],
            'descricao' => $destaque['descricao'],
            'empresa_id' => auth()->user()->empresa_id
        ]);
    }
    public function atualizarProdutoDestaque($destaque){

        return $this->produtoDestaque::where('uuid', $destaque['uuid'])->update([
            'designacao' => $destaque['designacao'],
            'descricao' => $destaque['descricao'],
        ]);
    }
    public function deletarProdutoDestaque($uuid){
        return $this->produtoDestaque::where('uuid', $uuid)->delete();
    }
}
