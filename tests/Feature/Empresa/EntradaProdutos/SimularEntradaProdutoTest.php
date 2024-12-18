<?php

namespace Empresa\EntradaProdutos;

use App\Application\UseCase\Empresa\EntradaProdutos\FazerEntradaProduto;
use App\Application\UseCase\Empresa\EntradaProdutos\SimularEntradaProduto;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SimularEntradaProdutoTest extends TestCase
{
    public function testDeveFazerEntradaProduto(){
        $input = [
            "numeracaoFatura" => 'FR AGT2023/1',
            "fornecedorId" => 1,
            "armazemId" => 34,
            "formaPagamentoId" => 1,
            "dataEntrada" => '2023-08-22',
            "dataFaturaFornecedor" => '2023-08-21',
            "descontoValor" => 0,
            "descontoPercentagem" => 0,
            "totalRetencao" => 0,
            "statusPagamento" => 1, //Pago
            "descricao" => null,
            "items" =>[
                [
                    "produtoId" => 1,
                    "precoVenda" => 2000,
                    "precoCompra" => 1000,
                    "quantidade" => 4,
                    "descontoPercentagem" => 0,
                    "descontoValor" => 0,
                    "taxaIva" => 0
                ],
                [
                    "produtoId" => 1,
                    "precoVenda" => 2000,
                    "precoCompra" => 1000,
                    "quantidade" => 5,
                    "descontoPercentagem" => 100,
                    "descontoValor" => 0,
                    "taxaIva" => 14,
                ]
            ]
        ];
        DB::beginTransaction();
        $entradaProduto = new SimularEntradaProduto(new DatabaseRepositoryFactory());
        $output = $entradaProduto->execute(new Request($input));
        $this->assertSame(18000, $output->getData()->total_venda);
        $this->assertSame(9000, $output->getData()->total_compras);
        $this->assertSame(5000, $output->getData()->total_desconto);
        $this->assertSame(1400, $output->getData()->total_iva);
        $this->assertSame(9000, $output->getData()->totalLucro);
        $this->assertNotNull($output);
        DB::rollBack();
    }


}
