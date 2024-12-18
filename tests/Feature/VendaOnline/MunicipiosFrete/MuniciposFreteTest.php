<?php

namespace Tests\Feature\VendaOnline\MunicipiosFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\AtualizarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\CadastrarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\EliminarMunicipioFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFretePelaProvincia;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MuniciposFreteTest extends TestCase
{
    public function testListarTodosMunicipiosFretePelaPronvincia(){

        $provinciaId = 1;
        DB::beginTransaction();
        $getMunicipiosFrete = new GetMunicipiosFretePelaProvincia(new DatabaseRepositoryFactory());
        $output = $getMunicipiosFrete->execute($provinciaId);
        $this->assertNotNull($output);
        DB::rollBack();
    }
    public function testListarTodosMunicipiosFrete(){

        $search = null;
        DB::beginTransaction();
        $getMunicipiosFrete = new GetMunicipiosFrete(new DatabaseRepositoryFactory());
        $output = $getMunicipiosFrete->execute($search);
        $this->assertNotNull($output);
        DB::rollBack();
    }
    public function testDeveCadastrarMunicipioFrete(){

        $input = [
            'designacao' => 'Bengo',
            'provinciaId' => 1,
            'valorEntrega' => 1000,
            'statusId' => 1
        ];
        DB::beginTransaction();
        $getMunicipiosFrete = new CadastrarMunicipioFrete(new DatabaseRepositoryFactory());
        $output = $getMunicipiosFrete->execute(new Request($input));

        $this->assertNotNull($output);
        DB::rollBack();
    }
    public function testDeveAtualizarMunicipioFrete(){

        $input1 = [
            'designacao' => 'Bengo',
            'provinciaId' => 1,
            'valorEntrega' => 1000,
            'statusId' => 1
        ];
        $input2 = [
            'designacao' => 'Bengo2',
            'provinciaId' => 1,
            'valorEntrega' => 2000,
            'statusId' => 1
        ];
        DB::beginTransaction();
        $cadastrarMunicipioFrete = new CadastrarMunicipioFrete(new DatabaseRepositoryFactory());
        $output1 = $cadastrarMunicipioFrete->execute(new Request($input1));

        $atualizarMunicipioFrete = new AtualizarMunicipioFrete(new DatabaseRepositoryFactory());
        $output2 = $atualizarMunicipioFrete->execute(new Request($input2), $output1->id);
        $this->assertNotNull($output2);
        $this->assertSame(1, $output2);
        DB::rollBack();
    }
    public function testDeveEliminarMunicipioFrete(){

        $input1 = [
            'designacao' => 'Bengo',
            'provinciaId' => 1,
            'valorEntrega' => 1000,
            'statusId' => 1
        ];

        DB::beginTransaction();
        $cadastrarMunicipioFrete = new CadastrarMunicipioFrete(new DatabaseRepositoryFactory());
        $output1 = $cadastrarMunicipioFrete->execute(new Request($input1));

        $atualizarMunicipioFrete = new EliminarMunicipioFrete(new DatabaseRepositoryFactory());
        $output2 = $atualizarMunicipioFrete->execute($output1->id);
        $this->assertNotNull($output2);
        $this->assertSame(1, $output2);
        DB::rollBack();
    }
}
