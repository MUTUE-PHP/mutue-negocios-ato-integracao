<?php

namespace App\Infra\Repository\Empresa;
use App\Domain\Entity\Empresa\Produtos\Produto;
use App\Models\empresa\Factura;
use App\Models\empresa\Produto as ProdutoDatabase;
use App\Models\empresa\ExistenciaStock as ExistenciaStockDatabase;
use App\Models\empresa\ProdutoImagem as ProdutoImagemDatabase;
use App\Repositories\Empresa\TraitConstrutorCarateristica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdutoRepository
{

    use TraitConstrutorCarateristica;

    public function salvar(Produto $produto)
    {
        return ProdutoDatabase::create([
            'uuid' => Str::uuid(),
            'designacao' => $produto->getDesignacao(),
            'preco_venda' => $produto->getPrecoVenda(),
            'pvp' => $produto->getPvp(),
            'preco_compra' => $produto->getPrecoCompra(),
            'tipoServicoId' => $produto->getTipoServidoId(),
            'categoria_id' => $produto->getCategoriaId(),
            'orderCategoria1' => $produto->getOrderCategoria1(),
            'orderCategoria2' => $produto->getOrderCategoria2(),
            'orderCategoria3' => $produto->getOrderCategoria3(),
            'unidade_medida_id' => $produto->getUnidadeMedidaId(),
            'fabricante_id' => $produto->getFabricanteId(),
            'user_id' => auth()->user()->id ?? 35,
            'canal_id' => 2,
            'status_id' => $produto->getStatusId(),
            'codigo_taxa' => $produto->getTaxaIvaId(),
            'motivo_isencao_id' => $produto->getMotivoIsencao()??8,
            'quantidade_minima' => $produto->getQuantidadeMinima(),
            'quantidade_critica' => $produto->getQuantidadeCritica(),
            'imagem_produto' => $produto->getImagemProduto(),
            'referencia' => $produto->getReferencia(),
            'codigo_barra' => $produto->getCodigoBarra(),
            'data_expiracao' => $produto->getDataExpiracao(),
            'descricao' => $produto->getDescricao(),
            'stocavel' => $produto->getStocavel(),
            'venda_online' => $produto->getVendaOnline(),
            'cartaGarantia' => $produto->getCartaGarantia()?"Y":"N",
            'tempoGarantiaProduto' => $produto->getTempoGarantiaProduto(),
            'tipoGarantia' => $produto->getTipoGarantia(),
            'centroCustoId' => $produto->getCentroCustoId(),
            'empresa_id' => auth()->user()->empresa_id ?? 53
        ]);
    }

    public function eliminarProduto($produtoId){
        ProdutoDatabase::where('id', $produtoId)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->delete();
    }
    public function update(Produto $produto, $produtoId)
    {
        return ProdutoDatabase::where('id', $produtoId)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->update([
                'designacao' => $produto->getDesignacao(),
                'preco_venda' => $produto->getPrecoVenda(),
                'pvp' => $produto->getPvp(),
                'preco_compra' => $produto->getPrecoCompra(),
                'tipoServicoId' => $produto->getTipoServidoId(),
                'categoria_id' => $produto->getCategoriaId(),
                'orderCategoria1' => $produto->getOrderCategoria1(),
                'orderCategoria2' => $produto->getOrderCategoria2(),
                'orderCategoria3' => $produto->getOrderCategoria3(),
                'unidade_medida_id' => $produto->getUnidadeMedidaId(),
                'fabricante_id' => $produto->getFabricanteId(),
                'user_id' => auth()->user()->id,
                'codigo_barra' => $produto->getCodigoBarra(),
                'referencia' => $produto->getReferencia(),
                'canal_id' => 2,
                'status_id' => $produto->getStatusId(),
                'codigo_taxa' => $produto->getTaxaIvaId(),
                'motivo_isencao_id' => $produto->getMotivoIsencao()??8,
                'quantidade_minima' => $produto->getQuantidadeMinima(),
                'quantidade_critica' => $produto->getQuantidadeCritica(),
                'imagem_produto' => $produto->getImagemProduto(),
                'data_expiracao' => $produto->getDataExpiracao(),
                'descricao' => $produto->getDescricao(),
                'stocavel' => $produto->getStocavel(),
                'venda_online' => $produto->getVendaOnline(),
                'cartaGarantia' => $produto->getCartaGarantia()?"Y":"N",
                'tempoGarantiaProduto' => $produto->getTempoGarantiaProduto(),
                'tipoGarantia' => $produto->getTipoGarantia(),
                'centroCustoId' => $produto->getCentroCustoId()
            ]);
    }

    public function salvarImagensAdicional($urlImagem, $produtoId)
    {
        return ProdutoImagemDatabase::create([
            'url' => $urlImagem,
            'produto_id' => $produtoId
        ]);
    }
    public function getProdutoPeloCentroCustoId($centroCustoId){


        return ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])
            ->whereHas('produto', function ($query) use ($centroCustoId) {
                $query->where('centroCustoId', $centroCustoId);
            })->where('empresa_id', auth()->user()->empresa_id)
            ->get();
    }

    public function getProdutoPeloTipoServico($tipoServicoId){
        return ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])
            ->whereHas('produto', function ($query) use($tipoServicoId){
                $query->where('centroCustoId', session()->get('centroCustoId'));
                $query->where('tipoServicoId', $tipoServicoId);
                $query->where('status_id', 1);
                $query->whereNotIn('id', [39]);
            })
            ->where('empresa_id', auth()->user()->empresa_id)
            ->get();
    }
    public function getProdutoArmazemIdPeloCentroCustoId2($centroCustoId, $armazemId)
    {
        return ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])
            ->where('armazem_id', $armazemId)
            ->whereHas('produto', function ($query) use ($centroCustoId) {
                $query->where('centroCustoId', $centroCustoId)
                    ->where('stocavel', 'Sim');
            })
            ->where('empresa_id', auth()->user()->empresa_id)
            ->get();
    }

    public function getProdutoArmazemIdPeloCentroCustoId($filtro)
    {
        $centroCustoId = $filtro['centroCustoId'];
        $search = $filtro['search'];
        return ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])
            ->where('armazem_id', $filtro['armazemId'])
            ->whereHas('produto', function ($query) use ($centroCustoId, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('designacao', 'like', '%' . $search . '%')
                        ->orwhere('referencia', 'like', '%' . $search . '%');
                })
                    ->where('centroCustoId', $centroCustoId)
                    ->where('stocavel', 'Sim');
            })
            ->where('empresa_id', auth()->user()->empresa_id)
            ->get();
    }

    public function getProdutosPeloCentroCusto($filter){
        return ProdutoDatabase::with(['tipoTaxa', 'statuGeral', 'motivoIsencao', 'categoria', 'tipoServico'])
            ->filter($filter)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('centroCustoId', session()->get('centroCustoId'))
            ->paginate();
    }
    public function getProduto($id)
    {

        return ProdutoDatabase::with(['tipoTaxa', 'statuGeral', 'motivoIsencao', 'categoria'])
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('id', $id)->first();

    }
    public function getProdutos($filter = null)
    {

        return ProdutoDatabase::with(['tipoTaxa', 'statuGeral', 'motivoIsencao', 'categoria'])
            ->filter($filter)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('centroCustoId', !$filter["centroCustoId"]|| empty($filter["centroCustoId"]) ?session()->get("centroCustoId"): $filter["centroCustoId"])
            ->paginate();

    }
    public function getProdutoDescricao($uuid){
        $produto = ProdutoDatabase::where('uuid', $uuid)
            ->first();
        $produto['caracteristicas'] =  $this->construirCaracteristicas($produto);
        return $produto;
    }
    public function getProdutoUuid($uuid)
    {
        return ProdutoDatabase::with(['produtoImagens','valorCaracteristica'])->where('uuid', $uuid)
            ->where('empresa_id', auth()->user()->empresa_id)->first();
    }

    public function getProdutosPorArmazem2($armazemId)
    {
        return ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])->where('armazem_id', $armazemId)
            ->where('empresa_id', auth()->user()->empresa_id ?? 53)
            ->get();
    }

    public function getProdutosMaisVendidos2($search)
    {
        $produtos = ProdutoDatabase::with(['valorCaracteristica','existenciaEstock', 'produtoImagens', 'categoria', 'status', 'empresa'])
            ->select(
            'produtos.designacao',
            'produtos.imagem_produto',
            'produtos.preco_venda',
            'produtos.preco_compra',
            DB::raw('SUM(factura_items.quantidade_produto) as qtVendidos'),
            'produtos.id',
            'produtos.stocavel')
            ->join('factura_items', 'produtos.id', '=', 'factura_items.produto_id')
            ->join('facturas', 'facturas.id', '=', 'factura_items.factura_id')
            ->where('produtos.venda_online', 'Y')
            ->groupBy(
                'produtos.id')
            ->orderByDesc(DB::raw('SUM(factura_items.quantidade_produto)'))
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
    public function totalUsers($produtoId)
    {
        return DB::connection('mysql2')->table('classificacao')
            ->where('produto_id', $produtoId)
            ->count();
    }

    public function getProdutosMaisVendidos($search)
    {
        return DB::select('
        select produtos.designacao, produtos.preco_venda, produtos.preco_compra,
        sum(factura_items.quantidade_produto) as qtVendidos, produtos.id,produtos.stocavel
        from facturas
        INNER JOIN factura_items ON facturas.id = factura_items.factura_id
        INNER JOIN produtos ON produtos.id = factura_items.produto_id
        WHERE facturas.empresa_id = "' . auth()->user()->empresa_id . '"
        GROUP by factura_items.descricao_produto, produtos.designacao,
        produtos.preco_venda, produtos.preco_compra, produtos.id,produtos.stocavel
        order by sum(factura_items.quantidade_produto) desc');
    }

    public function getProdutosPorArmazem($search = null, $armazemId){
        $existencias = ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])->where('armazem_id', $armazemId)
            ->whereHas('produto', function($query){
                $query->where('centroCustoId', session()->get('centroCustoId'));
            })
            ->where('empresa_id',auth()->user()->empresa_id)
            ->search(trim($search))
            ->get();
        $produtos = [];
        foreach ($existencias as $key => $existencia) {
            $produtos[$key]['produtoId'] = $existencia['produto_id'];
            $produtos[$key]['armazemId'] = $existencia['armazem_id'];
            $produtos[$key]['codigoProduto'] = $existencia['produto']['referencia'];
            $produtos[$key]['codigoBarraProduto'] = $existencia['produto']['codigo_barra'];
            $produtos[$key]['nomeProduto'] = $existencia['produto']['designacao'];
            $produtos[$key]['precoVendaProduto'] = $existencia['produto']['preco_venda'];
            $produtos[$key]['pvp'] = $existencia['produto']['pvp'];
            $produtos[$key]['iva'] = ($existencia['produto']['preco_venda'] * $existencia['produto']['tipoTaxa']['taxa']) / 100;
            $produtos[$key]['produtoCartaGarantia'] = $existencia['produto']['cartaGarantia'];
            $produtos[$key]['tempoGarantiaProduto'] = $existencia['produto']['tempoGarantiaProduto'];
            $produtos[$key]['tipoGarantia'] = $existencia['produto']['tipoGarantia'];
            $produtos[$key]['precoCompraProduto'] = $existencia['produto']['preco_compra'];
            $produtos[$key]['quantidadeStock'] = $existencia['quantidade'];
            $produtos[$key]['isEstocavel'] = $existencia['produto']['stocavel'];
            $produtos[$key]['quantidadeMinima'] = $existencia['produto']['quantidade_minima'];
            $produtos[$key]['quantidadeCritica'] = $existencia['produto']['quantidade_critica'];
            $produtos[$key]['taxaIva'] = $existencia['produto']['tipoTaxa']['taxa'];
        }
        return $produtos;
    }

    public function getProdutoIdArmazemId($produtoId, $armazemId)
    {
        return ExistenciaStockDatabase::with(['produto', 'produto.tipoTaxa'])->where('armazem_id', $armazemId)
            ->where('empresa_id', auth()->user()->empresa_id ?? 53)
            ->where('produto_id', $produtoId)
            ->first();
    }
}
