<?php

namespace Tests\Feature\VendaOnline\Pagamentos;
use App\Application\UseCase\VendasOnline\PagamentoCompras\EnviarPagamentoCompraVendaOnline;
use App\Application\UseCase\VendasOnline\PagamentoCompras\ListarPagamentosUtilizadorEspecificoAutenticado;
use App\Application\UseCase\VendasOnline\PagamentoCompras\ListarTodosPagamentosVendasOnline;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Models\empresa\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PagamentoCompraTest extends TestCase
{
    public function testDeveEnviarPagamentoCompra()
    {



        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            'comprovativoBancario' => UploadedFile::fake()->image('post-image.jpg'),
            'dataPagamentoBanco' => Carbon::now(),
            'formaPagamentoId' => 3,
            'bancoId' => 1,
            'statusPagamentoId' => 2, //Pendente
            'nomeUserEntrega' => 'Paulo João',
            'apelidoUserEntrega' => 'João',
            'enderecoEntrega' => 'Rua Osvaldo Roberto Maier, 442',
            'pontoReferenciaEntrega' => 'Congolenses, próximo a cimex',
            'telefoneUserEntrega' => '9236566044',
            'provinciaIdEntrega' => 1, //Luanda
            'municipioIdEntrega' => 1,
            'tipoEntregaId' => 1,
            'emailEntrega' => 'pauloggjoao@gmail.com',
            'observacaoEntrega' => null,
        ];
        DB::beginTransaction();
        $pagamentoCompra = new EnviarPagamentoCompraVendaOnline(new DatabaseRepositoryFactory());
        $output = $pagamentoCompra->execute(new Request($input));
        $this->assertNotNull($output);
        DB::rollBack();
    }

    public function testDeveListarTodosPagamentosDeUmUtilizador()
    {

        $user = User::find(35);
        $this->actingAs($user);
        $input = [
            'comprovativoBancario' => UploadedFile::fake()->image('post-image.jpg'),
            'dataPagamentoBanco' => Carbon::now(),
            'formaPagamentoId' => 3,
            'bancoId' => 1,
            'statusPagamentoId' => 2, //Pendente
            'nomeUserEntrega' => 'Paulo João',
            'apelidoUserEntrega' => 'João',
            'enderecoEntrega' => 'Rua Osvaldo Roberto Maier, 442',
            'pontoReferenciaEntrega' => 'Congolenses, próximo a cimex',
            'telefoneUserEntrega' => '9236566044',
            'provinciaIdEntrega' => 1, //Luanda
            'municipioIdEntrega' => 1,
            'tipoEntregaId' => 1,
            'emailEntrega' => 'pauloggjoao@gmail.com',
            'observacaoEntrega' => null,
        ];
        DB::beginTransaction();
        $pagamentoCompra = new EnviarPagamentoCompraVendaOnline(new DatabaseRepositoryFactory());
        $pagamentoCompra->execute(new Request($input));
        $search = null;
        $getPagamentos = new ListarPagamentosUtilizadorEspecificoAutenticado(new DatabaseRepositoryFactory());
        $output = $getPagamentos->execute($search);
        $this->assertNotNull($output);
        DB::rollBack();
    }

    public function testDeveListarTodosPagamentos()
    {
        $user = User::find(35);
        $this->actingAs($user);

        $input1 = [
            'comprovativoBancario' => UploadedFile::fake()->image('post-image.jpg'),
            'dataPagamentoBanco' => Carbon::now(),
            'formaPagamentoId' => 3,
            'bancoId' => 1,
            'statusPagamentoId' => 2, //Pendente
            'nomeUserEntrega' => 'Paulo João',
            'apelidoUserEntrega' => 'João',
            'enderecoEntrega' => 'Rua Osvaldo Roberto Maier, 442',
            'pontoReferenciaEntrega' => 'Congolenses, próximo a cimex',
            'telefoneUserEntrega' => '9236566044',
            'provinciaIdEntrega' => 1, //Luanda
            'municipioIdEntrega' => 1,
            'tipoEntregaId' => 1,
            'emailEntrega' => 'pauloggjoao@gmail.com',
            'observacaoEntrega' => null,
        ];
        DB::beginTransaction();
        $pagamentoCompra = new EnviarPagamentoCompraVendaOnline(new DatabaseRepositoryFactory());
        $pagamentoCompra->execute(new Request($input1));
        $search = null;
        $getPagamentos = new ListarTodosPagamentosVendasOnline(new DatabaseRepositoryFactory());
        $output = $getPagamentos->execute($search);
        $this->assertNotNull($output);
        DB::rollBack();
    }

}
