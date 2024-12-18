<?php

namespace Empresa;

use App\Application\UseCase\Empresa\CartaoCliente\CadastrarCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientes;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CartaoClienteTest extends TestCase
{
    use RefreshDatabase;
    public function testDeveCriarCartaoCliente(){

        $input = [
            "clienteId" => 1,
            "saldo" => 0,
            "dataEmissao" => '2023-08-17',
            "dataValidade" => '2024-08-17',
        ];
        DB::beginTransaction();
        $cadastrarCartaoCliente = new CadastrarCartaoCliente(new DatabaseRepositoryFactory());
        $output = $cadastrarCartaoCliente->execute(new Request($input));
        $this->assertNotNull($output);
        DB::rollBack();
    }

    public function testDeveListarTodosCartaoCliente(){

        $input1 = [
            "clienteId" => 1,
            "saldo" => 0,
            "dataEmissao" => '2023-08-17',
            "dataValidade" => '2024-08-17',
        ];
        $input2 = [
            "clienteId" => 2,
            "saldo" => 0,
            "dataEmissao" => '2023-08-17',
            "dataValidade" => '2024-08-17',
        ];
        DB::beginTransaction();
        $cadastrarCartaoCliente = new CadastrarCartaoCliente(new DatabaseRepositoryFactory());
        $cadastrarCartaoCliente->execute(new Request($input1));
        $cadastrarCartaoCliente->execute(new Request($input2));

        $search = null;
        $getCartaoClientes = new GetCartaoClientes(new DatabaseRepositoryFactory());
        $output = $getCartaoClientes->execute($search);

        dd($output->total());
        $this->assertSame(2, $output->total());
        DB::rollBack();
    }
}
