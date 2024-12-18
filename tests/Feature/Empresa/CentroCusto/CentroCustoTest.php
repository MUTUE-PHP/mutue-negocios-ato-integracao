<?php

namespace Empresa\CentroCusto;

use App\Application\UseCase\Empresa\CentrosDeCusto\AtualizarCentroCusto;
use App\Application\UseCase\Empresa\CentrosDeCusto\CadastrarCentroCusto;
use App\Application\UseCase\Empresa\CentrosDeCusto\EliminarCentroCusto;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentroCusto;
use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCusto;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

use Tests\TestCase;

class CentroCustoTest extends TestCase
{
    public function testDeveCriarCentroCusto()
    {

        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            "nome" => 'Mutue soluções tecnologicas',
            "endereco" => 'Luanda, Kinaxixi',
            "nif" => '500040003000',
            "cidade" => 'Luanda',
            "logotipo" => UploadedFile::fake()->image('post-image.jpg'),
            "email" => 'mutue@gmail.com',
            "website" => 'mutue.net',
            "telefone" => '932655210',
            "statusId" => 1,
            "pessoaContato" => 'Mutue soluções tecnologicas',
            "fileAlvara" => UploadedFile::fake()->image('post-image.jpg'),
            "fileNif" => UploadedFile::fake()->image('post-image.jpg'),
        ];
        DB::beginTransaction();
        $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
        $output = $cadastrarCentroCusto->execute(new Request($input));
        $this->assertNotNull($output);
        $this->assertSame("Mutue soluções tecnologicas", $output->nome);
        DB::rollBack();
    }

    public function testDeveListarTodosCentroCusto()
    {

        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            "nome" => 'Mutue soluções tecnologicas',
            "endereco" => 'Luanda, Kinaxixi',
            "nif" => '500040003000',
            "cidade" => 'Luanda',
            "logotipo" => UploadedFile::fake()->image('post-image.jpg'),
            "email" => 'mutue@gmail.com',
            "website" => 'mutue.net',
            "telefone" => '932655210',
            "statusId" => 1,
            "pessoaContato" => 'Mutue soluções tecnologicas',
            "fileAlvara" => UploadedFile::fake()->image('post-image.jpg'),
            "fileNif" => UploadedFile::fake()->image('post-image.jpg'),
        ];
        DB::beginTransaction();
        $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
        $cadastrarCentroCusto->execute(new Request($input));

        $getCentrosCusto = new GetCentrosCusto(new DatabaseRepositoryFactory());
        $output = $getCentrosCusto->execute();
        $this->assertNotNull($output);
        DB::rollBack();

    }
    public function testDeveListarUmCentroDeCusto()
    {
        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            "nome" => 'Mutue soluções tecnologicas',
            "endereco" => 'Luanda, Kinaxixi',
            "nif" => '500040003000',
            "cidade" => 'Luanda',
            "logotipo" => UploadedFile::fake()->image('post-image.jpg'),
            "email" => 'mutue@gmail.com',
            "website" => 'mutue.net',
            "telefone" => '932655210',
            "statusId" => 1,
            "pessoaContato" => 'Mutue soluções tecnologicas',
            "fileAlvara" => UploadedFile::fake()->image('post-image.jpg'),
            "fileNif" => UploadedFile::fake()->image('post-image.jpg'),
        ];
        DB::beginTransaction();
        $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
        $output1 = $cadastrarCentroCusto->execute(new Request($input));

        $getCentrosCusto = new GetCentroCusto(new DatabaseRepositoryFactory());
        $output2 = $getCentrosCusto->execute($output1->id);
        $this->assertNotNull($output2);
        $this->assertSame("Mutue soluções tecnologicas", $output2->nome);
        DB::rollBack();
    }

    public function testDeveAtualizarCentroCusto()
    {

        $user = User::find(35);
        $this->actingAs($user);

        $input1 = [
            "nome" => 'Mutue soluções tecnologicas',
            "endereco" => 'Luanda, Kinaxixi',
            "nif" => '500040003000',
            "cidade" => 'Luanda',
            "logotipo" => UploadedFile::fake()->image('post-image.jpg'),
            "email" => 'mutue@gmail.com',
            "website" => 'mutue.net',
            "telefone" => '932655210',
            "statusId" => 1,
            "pessoaContato" => 'Mutue soluções tecnologicas',
            "fileAlvara" => UploadedFile::fake()->image('post-image.jpg'),
            "fileNif" => UploadedFile::fake()->image('post-image.jpg'),
        ];
        $input2 = [
            "nome" => 'Mutue soluções tecnologicas',
            "endereco" => 'Luanda, Kinaxixi',
            "nif" => '500040003000',
            "cidade" => 'Luanda',
            "logotipo" => UploadedFile::fake()->image('post-image.jpg'),
            "email" => 'mutue@gmail.com',
            "website" => 'mutue.net',
            "telefone" => '932655210',
            "statusId" => 1,
            "pessoaContato" => 'Mutue soluções tecnologicas',
            "fileAlvara" => UploadedFile::fake()->image('post-image.jpg'),
            "fileNif" => UploadedFile::fake()->image('post-image.jpg'),
        ];
        DB::beginTransaction();
        $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
        $output1 = $cadastrarCentroCusto->execute(new Request($input1));

        $atualizarCentroCusto = new AtualizarCentroCusto(new DatabaseRepositoryFactory());
        $output2 = $atualizarCentroCusto->execute(new Request($input2), $output1->id);
        $this->assertNotNull($output2);
        DB::rollBack();
    }
    public function testDeveEliminarUmCentroCusto()
    {
        $user = User::find(35);
        $this->actingAs($user);

        $input = [
            "nome" => 'Mutue soluções tecnologicas',
            "endereco" => 'Luanda, Kinaxixi',
            "nif" => '500040003000',
            "cidade" => 'Luanda',
            "logotipo" => UploadedFile::fake()->image('post-image.jpg'),
            "email" => 'mutue@gmail.com',
            "website" => 'mutue.net',
            "telefone" => '932655210',
            "statusId" => 1,
            "pessoaContato" => 'Mutue soluções tecnologicas',
            "fileAlvara" => UploadedFile::fake()->image('post-image.jpg'),
            "fileNif" => UploadedFile::fake()->image('post-image.jpg'),
        ];
        DB::beginTransaction();
        $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
        $output1 = $cadastrarCentroCusto->execute(new Request($input));

        $eliminarCentroCusto = new EliminarCentroCusto(new DatabaseRepositoryFactory());
        $output2 = $eliminarCentroCusto->execute($output1->id);
        $this->assertNotNull($output2);
        DB::rollBack();

    }
}
