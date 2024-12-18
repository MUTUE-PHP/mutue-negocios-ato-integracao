<?php

namespace Empresa;

use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturacao;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class FaturacaoTest extends TestCase
{
    public function testDeveRetornarErroPagamentoDuploSemValorMulticaixaECash(){
        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            'clienteId' => 34,
            'numeroCartaoCliente' => null,
            'nomeCliente' => "Ramossoft",
            'nifCliente' => "999999999",
            'emailCliente' => "ramossoft@gmail.com",
            'telefoneCliente' => "923656044",
            'contaCorrenteCliente' => "31.1.2.1.2",
            'formaPagamentoId' => 6,
            'armazemId' => 34,
            'desconto' => 0,
            'isRetencao' => false,
            'tipoDocumento' => 1,
            'totalEntregue' => null,
            'totalMulticaixa' => 0,
            'totalCash' => 0,
            'items' => [
                [
                    'produtoId' => 414,
                    'armazemId'=> 34,
                    'nomeProduto'=> "maquina de lavar 2",
                    'precoVendaProduto'=> 150,
                    'precoCompraProduto'=> 100,
                    'quantidadeStock'=> 98,
                    'isEstocavel'=> "Sim",
                    'quantidadeMinima'=> 0,
                    'quantidadeCritica'=> 0,
                    'taxaIva'=> 0,
                    'quantidade'=> 1,
                    'desconto'=> 0,
                    'descontoGeral'=> 0,
                ]
            ]
        ];
        $emitirDocumentoFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Informe o valor multicaixa e valor cash");
        $emitirDocumentoFaturacao->execute(new Request($input));
    }
    public function testDeveEmitirFaturaRecibo()
    {

        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            'clienteId' => 34,
            'numeroCartaoCliente' => null,
            'nomeCliente' => "Ramossoft",
            'nifCliente' => "999999999",
            'emailCliente' => "ramossoft@gmail.com",
            'telefoneCliente' => "923656044",
            'contaCorrenteCliente' => "31.1.2.1.2",
            'formaPagamentoId' => 1,
            'armazemId' => 34,
            'desconto' => 0,
            'isRetencao' => false,
            'tipoDocumento' => 1,
            'totalEntregue' => null,
            'totalMulticaixa' => 0,
            'totalCash' => 0,
            'items' => [
                [
                    'produtoId' => 414,
                    'armazemId'=> 34,
                    'nomeProduto'=> "maquina de lavar 2",
                    'precoVendaProduto'=> 150,
                    'precoCompraProduto'=> 100,
                    'quantidadeStock'=> 98,
                    'isEstocavel'=> "Sim",
                    'quantidadeMinima'=> 0,
                    'quantidadeCritica'=> 0,
                    'taxaIva'=> 0,
                    'quantidade'=> 1,
                    'desconto'=> 0,
                    'descontoGeral'=> 0,
                ],
                [
                    'produtoId' => 110,
                    'armazemId'=> 34,
                    'nomeProduto'=> "MAQUINA DE LAVAR",
                    'precoVendaProduto'=> 100,
                    'precoCompraProduto'=> 0,
                    'quantidadeStock'=> 1,
                    'isEstocavel'=> "Sim",
                    'quantidadeMinima'=> 0,
                    'quantidadeCritica'=> 0,
                    'taxaIva'=> 0,
                    'quantidade'=> 1,
                    'desconto'=> 0,
                    'descontoGeral'=> 0,
                ],
            ]
        ];
        $emitirDocumentoFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
        $output = $emitirDocumentoFaturacao->execute(new Request($input));

    }
}
