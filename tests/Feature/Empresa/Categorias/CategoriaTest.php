<?php

namespace Empresa\Categorias;

use App\Application\UseCase\Empresa\Categorias\AtualizarCategoria;
use App\Application\UseCase\Empresa\Categorias\CadastrarCategoria;
use App\Application\UseCase\Empresa\Categorias\EliminarCategoria;
use App\Application\UseCase\Empresa\Categorias\GetCategorias;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    public function testDeveCadastrarUmaCategoria(){
        $input = [
            'designacao' => 'Telefones',
            'categoria_pai' => 31,
            'status_id' => 1
        ];
        DB::beginTransaction();
        $cadastrarCategoria = new CadastrarCategoria(new DatabaseRepositoryFactory());
        $output = $cadastrarCategoria->execute(new Request($input));
        $this->assertNotNull($output);
        $this->assertSame('Telefones', $output->designacao);
        DB::rollBack();
    }
    public function testDeveListarTodosCategorias(){

        $input1 = [
            'designacao' => 'Telefones',
            'categoria_pai' => 31,
            'status_id' => 1
        ];
        $input2 = [
            'designacao' => 'Telefone Iphone',
            'categoria_pai' => 31,
            'status_id' => 1
        ];
        DB::beginTransaction();
        $cadastrarCategoria = new CadastrarCategoria(new DatabaseRepositoryFactory());
        $cadastrarCategoria->execute(new Request($input1));
        $cadastrarCategoria->execute(new Request($input2));

        $getCategorias = new GetCategorias(new DatabaseRepositoryFactory());
        $output = $getCategorias->execute();
        $this->assertNotNull($output);
        DB::rollBack();
    }
    public function testDeveAtualizarCategoria(){

        $input1 = [
            'designacao' => 'Telefones',
            'categoria_pai' => 31,
            'status_id' => 1
        ];

        $input2 = [
            'designacao' => 'Telefones 001',
            'categoria_pai' => 31,
            'status_id' => 1
        ];

        DB::beginTransaction();
        $cadastrarCategoria = new CadastrarCategoria(new DatabaseRepositoryFactory());
        $output1 = $cadastrarCategoria->execute(new Request($input1));

        $atualizarCategoria = new AtualizarCategoria(new DatabaseRepositoryFactory());
        $output2 = $atualizarCategoria->execute(new Request($input2), $output1->id);
        $this->assertNotNull($output2);
        $this->assertSame(1, $output2);
        DB::rollBack();
    }
    public function testDeveEliminarCategoria(){

        $input1 = [
            'designacao' => 'Telefones 001',
            'categoria_pai' => 31,
            'status_id' => 1
        ];

        DB::beginTransaction();
        $cadastrarCategoria = new CadastrarCategoria(new DatabaseRepositoryFactory());
        $output1 = $cadastrarCategoria->execute(new Request($input1));
        $eliminarCategoria = new EliminarCategoria(new DatabaseRepositoryFactory());
        $output2 = $eliminarCategoria->execute($output1->id);
        $this->assertNotNull($output2);
        $this->assertSame(1, $output2);
        DB::rollBack();
    }

}
