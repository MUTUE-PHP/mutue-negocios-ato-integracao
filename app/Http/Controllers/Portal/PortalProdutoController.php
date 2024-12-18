<?php

namespace App\Http\Controllers\Portal;
use App\Models\empresa\Produto;
// use App\Repositories\Empresa\contracts\ProdutoRepositoryInterface;
use App\Repositories\Empresa\TraitConstrutorCarateristica;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PortalProdutoController extends Controller
{
    use TraitConstrutorCarateristica;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getPodutoDetalhes($uuid)
    {
        $produto = Produto::with('produtoImagens','valorCaracteristica','existenciaEstock','categoria', 'status', 'empresa')
        ->where('venda_online', 'Y')
            ->where('uuid',$uuid)
            ->first();
        $quantidade = DB::table('carrinho_produtos')->where('produto_id', $produto['id'])
            ->where('users_id', auth()->user()->id??35)->sum('quantidade');
        $produto['quantidadeCarrinho'] = (int)$quantidade;
        $produto['caracteristicas'] = $this->construirCaracteristicas($produto);
        $produto['classificacao'] = [
            [
                'classificacao' => 1,
                'users' => $this->countUsers(1, $produto['id']),
                'percentagem' => $this->calcularPercentagem(1, $produto['id'])
            ], [
                'classificacao' => 2,
                'users' => $this->countUsers(2, $produto['id']),
                'percentagem' => $this->calcularPercentagem(2, $produto['id'])
            ],
            [
                'classificacao' => 3,
                'users' => $this->countUsers(3, $produto['id']),
                'percentagem' => $this->calcularPercentagem(3, $produto['id'])
            ],
            [
                'classificacao' => 4,
                'users' => $this->countUsers(4, $produto['id']),
                'percentagem' => $this->calcularPercentagem(4, $produto['id'])
            ],
            [
                'classificacao' => 5,
                'users' => $this->countUsers(5, $produto['id']),
                'percentagem' => $this->calcularPercentagem(5, $produto['id'])
            ]
        ];

        return response()->json($produto);
    }
    public function countUsers($classificacao, $produtoId)
    {
        return DB::connection('mysql2')->table('classificacao')
            ->where('produto_id', $produtoId)
            ->where('num_classificacao', $classificacao)
            ->count();
    }
    public function totalUsers($produtoId)
    {
        return DB::connection('mysql2')->table('classificacao')
            ->where('produto_id', $produtoId)
            ->count();
    }

    public function calcularPercentagem($classificacao, $produtoId)
    {
        $totalUsers = $this->totalUsers($produtoId);
        if ($totalUsers <= 0) return 0;
        return (($classificacao * $this->countUsers($classificacao, $produtoId)) / $totalUsers) / 5;
    }
    public function pesquisarProdutoName(Request $request, $filtro = null)
    {
        $produtos = Produto::with('produtoImagens', 'categoria', 'status', 'classificacao')
        ->where('venda_online', 'Y')
        ->search(trim($filtro))
            ->orderByFilter($request->orderBy)
            ->get();
        return response()->json($produtos);
    }
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
