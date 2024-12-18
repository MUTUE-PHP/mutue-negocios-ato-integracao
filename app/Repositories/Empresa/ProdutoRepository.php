<?php

namespace App\Repositories\Empresa;

use App\Models\empresa\Classificacao;
use App\Models\empresa\ExistenciaStock;
use App\Models\empresa\Produto;
use App\Repositories\Empresa\contracts\ProdutoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Keygen\Keygen;
use Illuminate\Support\Facades\Storage;

class ProdutoRepository implements ProdutoRepositoryInterface
{

    use TraitConstrutorCarateristica;

    protected $entity;
    protected $classificacao;

    public function __construct(Produto $produto, Classificacao $classificacao)
    {
        $this->entity = $produto;
        $this->classificacao = $classificacao;
    }

    public function listarSeisProdutosMaisVendidos($centroCustoId)
    {

        return DB::select('
        select factura_items.descricao_produto,
        sum(factura_items.quantidade_produto) AS quantidade,
        SUM(factura_items.total_preco_produto) AS total_preco
               from facturas
               INNER JOIN factura_items ON facturas.id = factura_items.factura_id
               WHERE facturas.anulado=1 AND facturas.empresa_id = "' . auth()->user()->empresa_id . '" and facturas.centroCustoId = "' . $centroCustoId. '"
               GROUP by factura_items.descricao_produto
               order by sum(factura_items.quantidade_produto) desc
               LIMIT 6');
    }

    public function quantidadeProdutos($centroCustoId)
    {

        return $this->entity::where('empresa_id', auth()->user()->empresa_id)
        ->where('centroCustoId',$centroCustoId)
        ->count();
    }

    public function getProdutoComPaginacao($search)
    {
        return $this->entity::with(['existenciaEstock', 'existenciaEstock.armazens', 'tipoTaxa'])
            ->where('venda_online', 'N')
            ->where('empresa_id', auth()->user()->empresa_id)
             ->search(trim($search))->paginate();
    }
    public function listarProdutosPelaCategoriaUuid(Request $request, $uuid){
        $categoria = DB::table('categorias')->where('uuid', $uuid)
            ->first();
        $produtos = $this->entity::with(['valorCaracteristica', 'existenciaEstock','produtoImagens','status','categoria','empresa', 'existenciaEstock.armazens', 'tipoTaxa'])
            ->where('venda_online', 'Y')
            ->where('categoria_id', $categoria->id)
            ->search(trim($request->search))
            ->orderByFilter($request->orderBy)
            ->paginate();

        foreach ($produtos as $key => $produto) {
            $produtos[$key]['caracteristicas'] =  $this->construirCaracteristicas($produto);
            $produtos[$key]['classificacao'] = [
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
            $produtos[$key]['totalClassificacao'] = 0;
            $subClassificado = 0;
            $users = 0;
            foreach ($produtos[$key]['classificacao'] as $subtotal) {
                $subClassificado += $subtotal['classificacao'] * $subtotal['users'];
                $users += $subtotal['users'];
            }
            if ($subClassificado == 0 || $users == 0) {
                $produtos[$key]['totalClassificacao'] = 0;
            } else {
                $produtos[$key]['totalClassificacao'] = $subClassificado / $users;
            }
        }
        return $produtos;

    }

    public function listarProdutosPelaCategoria(Request $request, $categoriaId)
    {
        $produtos = $this->entity::with(['valorCaracteristica', 'existenciaEstock','produtoImagens','status','categoria','empresa', 'existenciaEstock.armazens', 'tipoTaxa'])
            ->where('venda_online', 'Y')
            ->where('categoria_id', $categoriaId)
            ->search(trim($request->search))
            ->orderByFilter($request->orderBy)
            ->paginate();

        foreach ($produtos as $key => $produto) {
            $produtos[$key]['caracteristicas'] =  $this->construirCaracteristicas($produto);
            $produtos[$key]['classificacao'] = [
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
            $produtos[$key]['totalClassificacao'] = 0;
            $subClassificado = 0;
            $users = 0;
            foreach ($produtos[$key]['classificacao'] as $subtotal) {
                $subClassificado += $subtotal['classificacao'] * $subtotal['users'];
                $users += $subtotal['users'];
            }
            if ($subClassificado == 0 || $users == 0) {
                $produtos[$key]['totalClassificacao'] = 0;
            } else {
                $produtos[$key]['totalClassificacao'] = $subClassificado / $users;
            }
        }
        return $produtos;
    }

    public function mv_listarComentarioPorProduto($produtoId)
    {
        return $this->classificacao::where('produto_id', $produtoId)->get();
    }

    public function listarProdutosRelacionais($uuid){

        $produto = $this->entity::where('uuid', $uuid)->first();
        if($produto){
            $orderCategoria1 = $produto['orderCategoria1'];
            $categoriaId = $produto['categoria_id'];
            $produtos = $this->entity::with(['favorito','valorCaracteristica','existenciaEstock', 'produtoImagens', 'categoria', 'status', 'empresa'])
                ->where('venda_online', 'Y')
                ->where('uuid', '!=', $uuid)
                ->where(function($query)use($categoriaId, $orderCategoria1){
                    $query->where('orderCategoria1', $orderCategoria1)
                        ->orwhere('categoria_id', $categoriaId);
                })
                ->paginate(15);

            foreach ($produtos as $key => $produto) {
                $produtos[$key]['isFavorito'] = count($produto['favorito']) > 0?true:false;
                $produtos[$key]['caracteristicas'] =  $this->construirCaracteristicas($produto);
                $produtos[$key]['classificacao'] = [
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
                $produtos[$key]['totalClassificacao'] = 0;
                $subClassificado = 0;
                $users = 0;
                foreach ($produtos[$key]['classificacao'] as $subtotal) {
                    $subClassificado += $subtotal['classificacao'] * $subtotal['users'];
                    $users += $subtotal['users'];
                }
                if ($subClassificado == 0 || $users == 0) {
                    $produtos[$key]['totalClassificacao'] = 0;
                } else {
                    $produtos[$key]['totalClassificacao'] = $subClassificado / $users;
                }
            }
            return $produtos;

        }
    }
    public function mv_listarProdutos($request, $search)
    {
        $userId = isset($request['userId'])? $request['userId']:null;
        session()->put('USER_FAVORITO_ID', $userId);
        $produtos = $this->entity::with(['favorito','valorCaracteristica','existenciaEstock', 'produtoImagens', 'categoria', 'status', 'empresa'])
            ->where('venda_online', 'Y')
            ->checkAuth($userId)
            ->search(trim($request->search))
            ->orderByFilter($request->orderBy)
            ->paginate(15);

        foreach ($produtos as $key => $produto) {
            $produtos[$key]['isFavorito'] = count($produto['favorito']) > 0?true:false;
            $produtos[$key]['caracteristicas'] =  $this->construirCaracteristicas($produto);
            $produtos[$key]['classificacao'] = [
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
            $produtos[$key]['totalClassificacao'] = 0;
            $subClassificado = 0;
            $users = 0;
            foreach ($produtos[$key]['classificacao'] as $subtotal) {
                $subClassificado += $subtotal['classificacao'] * $subtotal['users'];
                $users += $subtotal['users'];
            }
            if ($subClassificado == 0 || $users == 0) {
                $produtos[$key]['totalClassificacao'] = 0;
            } else {
                $produtos[$key]['totalClassificacao'] = $subClassificado / $users;
            }
        }
        return $produtos;
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

    public function countUsers($classificacao, $produtoId)
    {
        return DB::connection('mysql2')->table('classificacao')
            ->where('produto_id', $produtoId)
            ->where('num_classificacao', $classificacao)
            ->count();
    }

    public function getProdutosSemPaginacao($armazemId)
    {
        $produtos = $this->entity::with(['tipoTaxa', 'statuGeral', 'motivoIsencao'])
            ->whereHas('existenciaEstock', function ($query) use ($armazemId) {
                $query->where('armazem_id', $armazemId);
            })
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('centroCustoId', session()->get('centroCustoId'))
            ->where('stocavel', 'Sim')
            ->get();
        return $produtos;
    }

    public function getProdutos($search = null, $vendaOnline = 'N')
    {
       

        $produtos = $this->entity::with(['tipoTaxa', 'statuGeral', 'motivoIsencao'])
            ->where('empresa_id', auth()->user()->empresa_id)
            ->search(trim($search))
            ->vendaOnline($vendaOnline)
            ->paginate(15);

        return $produtos;
    }

    public function getProdutoPaginate()
    {
        $produtos = $this->entity::with(['tipoTaxa', 'statuGeral', 'motivoIsencao'])
            ->where('empresa_id', auth()->user()->empresa_id)->paginate();
        return $produtos;
    }

    public function listarProdutosPeloIdArmazem($armazemId, $centroCustoId = null)
    {
        $produtos = $this->entity::with(['tipoTaxa', 'motivoIsencao'])
            ->whereHas("existenciaEstock", function ($q) use ($armazemId) {
                $q->where('armazem_id', $armazemId);
            })
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('centroCustoId', $centroCustoId)
            ->get();
        return $produtos;
    }

    public function getProduto(int $id)
    {
        $produto = $this->entity::with(['tipoTaxa', 'statuGeral', 'motivoIsencao'])->where('empresa_id', auth()->user()->empresa_id)
            ->where('id', $id)
            ->first();
        return $produto;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $produtId = DB::table('produtos')->insertGetId([
                'uuid' => Str::uuid(),
                'designacao' => $request->designacao,
                'preco_venda' => $request->preco_venda ?? 0,
                'preco_compra' => $request->preco_compra ?? 0,
                'categoria_id' => $request->categoria_id,
                'unidade_medida_id' => $request->unidade_medida_id,
                'fabricante_id' => $request->fabricante_id,
                'venda_online' => isset($request['venda_online']) && $request['venda_online'] ? $request['venda_online'] : 'N',
                'user_id' => auth()->user()->id,
                'canal_id' => $request->canal_id ?? 2,
                'status_id' => $request->status_id ?? 1,
                'codigo_barra' => $request->codigo_barra ?? null,
                'codigo_taxa' => auth()->user()->empresa->tipo_regime_id != 1 ? 1 : $request->codigo_taxa,
                'motivo_isencao_id' => $request->motivo_isencao_id ?? 8, //Transmissão de bens e serviço não sujeita
                'quantidade_minima' => $request->quantidade_minima ?? 0,
                'quantidade_critica' => $request->quantidade_critica ?? 0,
                'imagem_produto' => $request['imagem_produto'] ? env('APP_URL') . "upload/" . $this->uploadFile($request['imagem_produto']) : env('APP_URL') . "upload/" . 'produtos/default.png',
                'referencia' => Keygen::numeric(9)->generate(),
                'data_expiracao' => $request->data_expiracao ?? NULL,
                'descricao' => $request->descricao ?? NULL,
                'stocavel' => $request->stocavel,
                'empresa_id' => auth()->user()->empresa_id,
                'centroCustoId' =>$request->centroCustoId
            ]);

            if ($request['imagens']) {
                $this->inserirArquivoAdicionaisDB($request, $produtId);
            }

            DB::table('existencias_stocks')->insertGetId([
                'produto_id' => $produtId,
                'armazem_id' => $request->armazem_id,
                'quantidade' => isset($request->stocavel) && $request->stocavel == 'Sim' ? $request->quantidade ?? 0 : 0,
                'user_id' => $request->user_id,
                'canal_id' => $request->canal_id ?? 2,
                'status_id' => $request->status_id ?? 1,
                'empresa_id' => auth()->user()->empresa_id,
                'created_at' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d')
            ]);

            DB::table('actualizacao_stocks')->insert([
                'produto_id' => $produtId,
                'empresa_id' => auth()->user()->empresa_id,
                'quantidade_antes' => 0,
                'quantidade_nova' => isset($request->stocavel) && $request->stocavel == 'Sim' ? $request->quantidade ?? 0 : 0,
                'user_id' => auth()->user()->id,
                'tipo_user_id' => 2, //empresa
                'canal_id' => 4,
                'status_id' => $request->status_id ?? 1,
                'armazem_id' => $request->armazem_id,
                'created_at' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d'),
                'centroCustoId' =>$request->centroCustoId
            ]);
            DB::commit();
            return $produtId;
        } catch (\Exception $th) {
            DB::rollBack();
        }
    }

    public function inserirArquivoAdicionaisDB($request, $produtoId): void
    {

        foreach ($request['imagens'] as $imagem) {
            $path = $this->uploadFile($imagem);
            DB::table('produto_imagens')->insertGetId([
                'url' => env('APP_URL') . "upload/" . $path,
                'produto_id' => $produtoId,
            ]);
        }
    }

    public function uploadFile($imagem)
    {
        return Storage::disk('public')->putFile('produtos', $imagem);
    }

    public function update(Request $request, int $produtoId)
    {
        $produto = Produto::where('id', $produtoId)
            ->where('empresa_id', auth()->user()->empresa_id)->first();

        $imagem = NULL;
        if ($request->hasFile('imagem_produto') && $request->imagem_produto->isValid()) {
            if (Storage::exists($produto->imagem_produto)) {
                Storage::delete($produto->imagem_produto);
            }
            $imagem = $request->imagem_produto->store("/produtos");
        }
        try {

            DB::beginTransaction();

            DB::table('produtos')->where('id', $produtoId)->update([
                'designacao' => $request->designacao,
                'preco_venda' => $request->preco_venda,
                'preco_compra' => $request->preco_compra ?? 0,
                'categoria_id' => $request->categoria_id,
                'unidade_medida_id' => $request->unidade_medida_id,
                'fabricante_id' => $request->fabricante_id,
                'status_id' => $request->status_id ?? 1,
                'codigo_taxa' => auth()->user()->empresa->tipo_regime_id != 1 ? 1 : $request->codigo_taxa,
                'motivo_isencao_id' => $request->codigo_taxa <= 0 ? 8 : $request->motivo_isencao_id, //Transmissão de bens e serviço não sujeita
                'quantidade_minima' => $request->quantidade_minima ?? 0,
                'quantidade_critica' => $request->quantidade_critica ?? 0,
                'imagem_produto' => $imagem ? $imagem : $request->imagem_produto,
                'codigo_barra' => $request->codigo_barra ?? null,
                'stocavel' => $request->stocavel,
                'centroCustoId' =>$request->centroCustoId
            ]);

            if ($request->stocavel == 'Não') {
                DB::table('existencias_stocks')->where('produto_id', $produto->id)->update([
                    'quantidade' => 0
                ]);
            }
            DB::commit();
            return $produtoId;
        } catch (\Exception $th) {
            DB::rollBack();
        }
    }

    public function listarProdutoComQuantidadeCritica()
    {
        $produtos = ExistenciaStock::with(['produtos'])
            ->whereHas('produtos', function ($q) {
                $q->where('produtos.quantidade_critica', '!=', 0)
                    ->where('produtos.empresa_id', auth()->user()->empresa_id)
                    ->where('produtos.centroCustoId', session()->get('centroCustoId'))
                    ->where('produtos.quantidade_critica', '>=', 'existencias_stocks.quantidade');
            })->get();

        return $produtos;
    }
}
