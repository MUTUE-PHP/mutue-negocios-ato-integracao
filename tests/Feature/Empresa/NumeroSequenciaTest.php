<?php
namespace Empresa;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\GetSequenciaDocumentos;
use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\SalvarSequenciaDocumento;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NumeroSequenciaTest extends TestCase
{
    //use RefreshDatabase;

    //use TraitSerieDocumento;

    /*

    public function testDeveCriarUmaSequenciaFatura(){

        $input = [
            'sequencia' => 100,
            'tipoDocumento' => 'FR',
            'serieDocumento' => 'AGT',
        ];
        $input = new Request($input);
        DB::beginTransaction();
        $sequenciaFatura = new SalvarSequenciaDocumento(new DatabaseRepositoryFactory());
        $output = $sequenciaFatura->execute($input);
        $this->assertSame(100, $output->sequencia);
        DB::rollBack();
    }
    public function testDeveListarSequenciaDosDocumentos(){

        $input1 = [
            'sequencia' => 100,
            'tipoDocumento' => 'FR',
            'serieDocumento' => 'AGT',
        ];
        $input2 = [
            'sequencia' => 102,
            'tipoDocumento' => 'FR',
            'serieDocumento' => 'AGT',
        ];
        $input1 = new Request($input1);
        $input2 = new Request($input2);
        DB::beginTransaction();
        $sequenciaFatura = new SalvarSequenciaDocumento(new DatabaseRepositoryFactory());
        $sequenciaFatura->execute($input1);
        $sequenciaFatura->execute($input2);
        $search = null;
        $getSequenciasDocumentos = new GetSequenciaDocumentos(new DatabaseRepositoryFactory());
        $output = $getSequenciasDocumentos->execute($search);
        $this->assertSame(2, count($output));
        DB::rollBack();
    }
    public function testDeveFiltrarSequenciaDocumento(){

        $input1 = [
            'sequencia' => 100,
            'tipoDocumento' => 'FR',
            'serieDocumento' => 'AGT',
        ];
        $input2 = [
            'sequencia' => 102,
            'tipoDocumento' => 'FT',
            'serieDocumento' => 'AGT',
        ];
        $input1 = new Request($input1);
        $input2 = new Request($input2);
        DB::beginTransaction();
        $sequenciaFatura = new SalvarSequenciaDocumento(new DatabaseRepositoryFactory());
        $sequenciaFatura->execute($input1);
        $sequenciaFatura->execute($input2);
        $search = 'FT';
        $getSequenciasDocumentos = new GetSequenciaDocumentos(new DatabaseRepositoryFactory());
        $output = $getSequenciasDocumentos->execute($search);
        $this->assertSame(1, count($output));
        DB::rollBack();
    }
    */

}
