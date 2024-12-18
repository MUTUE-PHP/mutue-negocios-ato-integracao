<?php

namespace Empresa\Produtos;

use App\Application\UseCase\Empresa\Produtos\AtualizarProduto;
use App\Application\UseCase\Empresa\Produtos\CadastrarProduto;
use App\Application\UseCase\Empresa\Produtos\GetProdutos;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProdutoTest extends TestCase
{
    public function testDeveCadastrarUmProduto()
    {
        $user = User::find(35);
        $this->actingAs($user);
        $input = [
            "designacao" => "Produto A",
            "codigo_barra" => "AAA-BBB-CCC",
            "dataExpiracao" => null,
            "categoria_id" => 1,
            "preco_compra" => 100,
            "preco_venda" => 100,
            "status_id" => 1,
            "quantidade_minima" => 5,
            "quantidade_critica" => 2,
            "stocavel" => 'Sim',
            "quantidade" => 5,
            "armazem_id" => 34,
            "unidade_medida_id" => 1,
            "fabricante_id" => 2,
            "codigo_taxa" => 1,
            "motivo_isencao_id" => 7,
            "venda_online" => 'Y',
            "imagem_produto" => UploadedFile::fake()->image('post-image.jpg'),
            "imagens" => [
                    UploadedFile::fake()->image('image1.jpg'),
                    UploadedFile::fake()->image('image2.jpg'),
            ],
            "descricao" => null,
        ];

        DB::beginTransaction();
        $cadastrarProduto = new CadastrarProduto(new DatabaseRepositoryFactory());
        $output = $cadastrarProduto->execute(new Request($input));
        $this->assertNotNull($output);
        $this->assertSame("Produto A", $output->designacao);
        DB::rollBack();
    }
    public function testDeveListarTodosProdutos(){

        $user = User::find(35);
        $this->actingAs($user);
        $input = [
            "designacao" => "Produto A",
            "codigo_barra" => "AAA-BBB-CCC",
            "dataExpiracao" => null,
            "categoria_id" => 1,
            "preco_compra" => 100,
            "preco_venda" => 100,
            "status_id" => 1,
            "quantidade_minima" => 5,
            "quantidade_critica" => 2,
            "stocavel" => 'Sim',
            "quantidade" => 5,
            "armazem_id" => 34,
            "unidade_medida_id" => 1,
            "fabricante_id" => 2,
            "codigo_taxa" => 1,
            "motivo_isencao_id" => 7,
            "venda_online" => 'Y',
            "imagem_produto" => UploadedFile::fake()->image('post-image.jpg'),
            "imagens" => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
            "descricao" => null,
        ];

        DB::beginTransaction();
        $cadastrarProduto = new CadastrarProduto(new DatabaseRepositoryFactory());
        $cadastrarProduto->execute(new Request($input));

        $filter = [
            'search' => null,
            'vendasOnline' => null
        ];
        $getListarProdutos  = new GetProdutos(new DatabaseRepositoryFactory());
        $output = $getListarProdutos->execute($filter);
        $this->assertNotNull($output);
    }
    public function testDeveAtualizarUmProduto()
    {
        $user = User::find(35);
        $this->actingAs($user);

        $input1 = [
            "designacao" => "Produto A",
            "codigo_barra" => "AAA-BBB-CCC",
            "dataExpiracao" => null,
            "categoria_id" => 1,
            "preco_compra" => 100,
            "preco_venda" => 100,
            "status_id" => 1,
            "quantidade_minima" => 5,
            "quantidade_critica" => 2,
            "stocavel" => 'Sim',
            "quantidade" => 5,
            "armazem_id" => 34,
            "unidade_medida_id" => 1,
            "fabricante_id" => 2,
            "codigo_taxa" => 1,
            "motivo_isencao_id" => 7,
            "venda_online" => 'Y',
            "imagem_produto" => UploadedFile::fake()->image('post-image.jpg'),
            "imagens" => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
            "descricao" => null,
        ];
        $input2 = [
            "designacao" => "Produto B",
            "codigo_barra" => "AAA-BBB-CCC",
            "dataExpiracao" => null,
            "categoria_id" => 1,
            "preco_compra" => 100,
            "preco_venda" => 100,
            "status_id" => 1,
            "quantidade_minima" => 5,
            "quantidade_critica" => 2,
            "stocavel" => 'Sim',
            "armazem_id" => 34,
            "unidade_medida_id" => 1,
            "fabricante_id" => 2,
            "codigo_taxa" => 1,
            "motivo_isencao_id" => 7,
            "venda_online" => 'Y',
            "antImagemProduto" => null,
            "imagem_produto" => UploadedFile::fake()->image('post-image.jpg'),
            "imagens" => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
            "descricao" => null,
        ];

        DB::beginTransaction();
        $cadastrarProduto = new CadastrarProduto(new DatabaseRepositoryFactory());
        $output1 = $cadastrarProduto->execute(new Request($input1));

        $atualizarProduto = new AtualizarProduto(new DatabaseRepositoryFactory());
        $output2 = $atualizarProduto->execute(new Request($input2), $output1->id);
        $this->assertNotNull($output2);
        DB::rollBack();
    }

}
