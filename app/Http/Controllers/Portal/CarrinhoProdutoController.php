<?php

namespace App\Http\Controllers\Portal;

use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Produto;
use App\Models\Portal\CarrinhoProduto;
use App\Repositories\Empresa\CouponDescontoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

class CarrinhoProdutoController extends Controller
{
    private $couponDescontoRepository;

    public function __construct(CouponDescontoRepository $couponDescontoRepository)
    {
        $this->couponDescontoRepository = $couponDescontoRepository;
    }

    public function getCarrinhoProdutos(Request $request, $message = null)
    {
        $taxaValorEntrega = 0;
        $produtos = CarrinhoProduto::with(['produto', 'produto.existenciaEstock', 'produto.tipoTaxa'])
            ->select('produto_id', 'quantidade')
            ->where('users_id', auth()->user()->id)->get();

        $taxaEntrega = DB::connection('mysql2')->table('parametros')
            ->where('empresa_id', 148)
            ->where('label', 'valor_estipulado_taxaEntrega')
            ->first();

        if (blank($taxaEntrega)) {
            $taxaEntrega = DB::connection('mysql2')->table('parametros')
                ->whereNull('empresa_id')
                ->where('label', 'valor_estipulado_taxaEntrega')
                ->first();
            $taxaValorEntrega = $taxaEntrega->valor;
        }else{
            $taxaValorEntrega = $taxaEntrega->valor;
        }


        $array = [
            "subtotalGeral" => 0,
            "totalCouponDesconto" => 0,
            "totalIva" => 0,
            "totalPagar" => 0,
            "totalEnvio" => 0,
            "cobrarTaxaEntrega" => true,
            "produtos" => []
        ];

        $subtotal = 0;
        $totalCouponDesconto = 0;
        $totalPagar = 0;
        $totalIva = 0;
        $totalEnvio = 0;
        $subtotalIva = 0;
        $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);

        foreach ($produtos as $key => $prod) {
            $subtotal += $prod['quantidade'] * $prod['produto']['preco_venda'];
            $totalPagar += $prod['quantidade'] * $prod['produto']['preco_venda'];
            $totalEnvio = 0;
            array_push($array['produtos'], [
                'id' => $prod['produto']['id'],
                'uuid' => $prod['produto']['uuid'],
                'designacao' => $prod['produto']['designacao'],
                'quantidade' => $prod['quantidade'],
                'existenciaStock' => $prod['produto']['existenciaEstock'][0]['quantidade'],
                'preco_venda' => $prod['produto']['preco_venda'],
                'pvp' => $prod['produto']['pvp'],
                'imagem_produto' => $prod['produto']['imagem_produto'],
                'subtotal' => $prod['quantidade'] * $prod['produto']['pvp'],
                'subtotalIva' => $this->calcularTotalIva($prod)
            ]);
            $totalIva += $array['produtos'][$key]['subtotalIva'];
        }

        $codigoDesconto = null;
        if (isset($request['codigoDesconto']) && $request['codigoDesconto']) {
            $codigoDesconto = $request['codigoDesconto'];
        }
        if ($codigoDesconto) {
            $coupon = $this->couponDescontoRepository->getCoupon($codigoDesconto);
            $isExpired = $this->couponDescontoRepository->isExpired($codigoDesconto);
            if ($coupon && !$isExpired) {
                $totalCouponDesconto = ($subtotal * $coupon['percentagem']) / 100;
            }
        }
        $totalPagar = $subtotal - $totalCouponDesconto + $totalIva + $totalEnvio;
        $valorTaxaEntrega = (double)$taxaEntrega->valor;
        if ($totalPagar >= $valorTaxaEntrega) {
            $array['cobrarTaxaEntrega'] = false;
        }
        $array['subtotalGeral'] = $subtotal;
        $array['totalIva'] = $subtotal + $subtotalIva;
        $array['totalCouponDesconto'] = $totalCouponDesconto;
        $array['totalPagar'] = $totalPagar;
        if($totalPagar >= $taxaValorEntrega){
            $array['cobrarTaxaEntrega'] = false;
        }
        $array['total_extenso'] = $f->format($totalPagar ?? 0);
        $array['totalIva'] = $totalIva;
        $array['totalEnvio'] = $totalEnvio;

        return response()->json([
            'data' => $array,
            'message' => $message ?? 'listar carrinho de compras'
        ]);
    }

    public function calcularTotalIva($prod)
    {
        $taxa = $prod['produto']['tipoTaxa']['taxa'];
        $precoVenda = $prod['produto']['preco_venda'];
        $quantidade = $prod['quantidade'];
        return ($precoVenda * $taxa * $quantidade) / 100;
    }

    public function getProduto($uuid)
    {
        return Produto::where('uuid', $uuid)->first();
    }

    public function addProdutoNoCarrinhoQty(Request $request, $quantidade)
    {

        $produto = $this->getProduto($request->uuid);
        if (!$produto) {
            return response()->json([
                'error' => "Produto não encontrado"
            ], 400);
        }
        $armazens = DB::connection('mysql2')->table('armazens')
            ->where('empresa_id', 148)
            ->get();
        $armazemId = $armazens[0]->id;

        $carrinho = CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
            ->where('users_id', auth()->user()->id)
            ->first();
        if ($carrinho) {
            $qty = $carrinho->quantidade + $quantidade;
            $carrinho->quantidade = $qty;
            $message = "Mais uma Unidade adicionado ao carrinho com sucesso";
        } else {
            $carrinho = new CarrinhoProduto();
            $carrinho->users_id = auth()->user()->id;
            $carrinho->produto_id = $produto->id;
            $qty = $carrinho->quantidade + $quantidade;
            $carrinho->quantidade = $qty;
            CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
                ->where('users_id', auth()->user()->id)
                ->first();
            $message = "Produto adicionado ao carrinho com sucesso!";
        }

        $semExistencia = DB::connection('mysql2')->table('existencias_stocks')
            ->where('produto_id', $produto['id'])
            ->where('armazem_id', $armazemId)
            ->where('quantidade', '>=', $qty)->first();
        if (!$semExistencia) {
            return response()->json([
                'error' => "Quantidade indisponivel no stock"
            ], 400);
        }
        $carrinho->save();


        return $this->getCarrinhoProdutos($request, $message);

    }

    public function addProdutoNoCarrinho(Request $request)
    {
        $produto = $this->getProduto($request->uuid);
        if (!$produto) {
            return response()->json([
                'error' => "Produto não encontrado"
            ], 400);
        }
        $armazens = DB::connection('mysql2')->table('armazens')
            ->where('empresa_id', 148)
            ->get();
        $armazemId = $armazens[0]->id;

        $carrinho = CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
            ->where('users_id', auth()->user()->id)
            ->first();
        if ($carrinho) {

            $qty = $carrinho->quantidade + 1;

            $carrinho->quantidade = $qty;
            $message = "Mais uma Unidade adicionado ao carrinho com sucesso";
        } else {
            $carrinho = new CarrinhoProduto();
            $carrinho->users_id = auth()->user()->id;
            $carrinho->produto_id = $produto->id;
            $qty = $carrinho->quantidade + 1;

            $carrinho->quantidade = $qty;

            CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
                ->where('users_id', auth()->user()->id)
                ->first();
            $message = "Produto adicionado ao carrinho com sucesso!";
        }

        $semExistencia = DB::connection('mysql2')->table('existencias_stocks')
            ->where('produto_id', $produto['id'])
            ->where('armazem_id', $armazemId)
            ->where('quantidade', '>=', $qty)->first();


        if (!$semExistencia) {
            return response()->json([
                'error' => "Quantidade indisponivel no stock"
            ], 400);
        }
        $carrinho->save();


        return $this->getCarrinhoProdutos($request, $message);
    }

    public function getCarrinhoProduto(Request $request)
    {
        $produto = $this->getProduto($request->uuid);
        if (!$produto) {
            return response()->json([
                'error' => "Produto não encontrado"
            ], 400);
        }
        $carrinho = CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
            ->where('users_id', auth()->user()->id)
            ->first();
        if (!$carrinho) {
            return response()->json([
                'data' => null,
                'message' => "Produto não encontrado no carrinho"
            ]);
        }
        return response()->json([
            'data' => $carrinho,
            'message' => 'Produto encontrado no carrinho'
        ]);
    }

    public function removerCarrinho(Request $request)
    {
        CarrinhoProduto::where('produto_id', $request->id)
            ->where('users_id', auth()->user()->id)
            ->delete();
        $message = "Produto removido com sucesso!";
        return $this->getCarrinhoProdutos($request, $message);
    }

    public function decreaseCarrinhoQtyProduto(Request $request)
    {
        $message = "";
        $produto = $this->getProduto($request->uuid);
        if (!$produto) {
            return response()->json([
                'error' => "Produto não encontrado"
            ]);
        }
        $carrinho = CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
            ->where('users_id', auth()->user()->id)
            ->first();

        if (!$carrinho) {
            return response()->json([
                'data' => null,
                'message' => "Produto não encontrado no carrinho"
            ]);
        }

        if (isset($request['codigoDesconto']) && $request['codigoDesconto']) {
            $codigoDesconto = $request['codigoDesconto'];
        } else {
            $codigoDesconto = null;
        }

        if ($carrinho && ($carrinho->quantidade - 1 > 0)) {
            $carrinho->quantidade = $carrinho->quantidade - 1;
            $carrinho->save();
            $message = "Mais uma unidade reduzida com sucesso!";
        } else {
            CarrinhoProduto::where('id', $carrinho->id)->delete();
            $message = "Produto removido com sucesso!";
        }
        return $this->getCarrinhoProdutos($request, $message);
    }

    public function addCouponDesconto(Request $request)
    {
        $coupon = $this->couponDescontoRepository->getCoupon($request->codigoDesconto);
        if (!$coupon) {
            return response()->json([
                'data' => null,
                'message' => 'Coupon de desconto não existe'
            ]);
        }
        $couponExpirado = $this->couponDescontoRepository->isExpired($request->codigoDesconto);
        if ($couponExpirado) {
            return response()->json([
                'data' => null,
                'message' => 'Coupon de desconto expirado'
            ]);
        }

        $message = "cupon desconto aplicado";
        return $this->getCarrinhoProdutos($request, $message);

        // $totalDesconto = $this->couponDescontoRepository->calcularCoupon($request->totalSemDesconto, $coupon->percentagem);
        // $totalPagar = $request->totalSemDesconto - $totalDesconto;
        // return response()->json([
        //     'data' =>[
        //         'totalCouponDesconto' => $totalDesconto,
        //         'totalPagar'=> $totalPagar
        //     ],
        //     'message'=>'Operação realizada com sucesso!'
        // ], 200);
    }

    public function encreaseCarrinhoQtyProduto(Request $request)
    {
        $message = "";
        $produto = $this->getProduto($request->uuid);
        if (!$produto) {
            return response()->json([
                'error' => "Produto não encontrado"
            ]);
        }
        $carrinho = CarrinhoProduto::with('produto')->where('produto_id', $produto->id)
            ->where('users_id', auth()->user()->id)
            ->first();

        if (!$carrinho) {
            return response()->json([
                'data' => null,
                'message' => "Produto não encontrado no carrinho"
            ]);
        }

        if (isset($request['codigoDesconto']) && $request['codigoDesconto']) {
            $codigoDesconto = $request['codigoDesconto'];
        } else {
            $codigoDesconto = null;
        }

        if ($carrinho) {
            $carrinho->quantidade = $carrinho->quantidade + 1;
            $carrinho->save();
            $message = "Mais uma unidade reduzida com sucesso!";
        } else {
            CarrinhoProduto::where('id', $carrinho->id)->delete();
            $message = "Produto removido com sucesso!";
        }
        return $this->getCarrinhoProdutos($request, $message);
    }
}
