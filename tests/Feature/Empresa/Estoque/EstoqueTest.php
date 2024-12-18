<?php

namespace Empresa\Estoque;

use App\Application\UseCase\Empresa\Estoque\AtualizarEstoque;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class EstoqueTest extends TestCase
{

    public function testDeveAtualizarEstoque(){

        $user = User::find(35);
        $this->actingAs($user);
        $input = [
            'produtoId' => 611,
            'quantidadeAnterior' => 1,
            'quantidadeNova' => 2,
            'armazemId' => 34,
            'descricao' => null,
            'centroCustoId' => 53
        ];
        $atualizarEstoque = new AtualizarEstoque(new DatabaseRepositoryFactory());
        $output = $atualizarEstoque->execute(new Request($input));
        $this->assertNotNull($output);
    }

}
