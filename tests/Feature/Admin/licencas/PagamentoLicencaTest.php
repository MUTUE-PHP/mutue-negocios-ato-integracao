<?php

namespace Admin\licencas;

use App\Application\UseCase\Admin\PagarLicenca;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PagamentoLicencaTest extends TestCase
{
    public function testDeveFazerPagamentoLicenca(){
        /*
        1 -x gera uma fatura de pagamento
        2 - xFaz o pagamento da tabela pagamento
        1 - xverifica se pagamento com mesma operação bancaria já existe
        3 - Aguarde a validação ou fazer a validação automatica,
        4 - altera o status da fatura como pago
        5 - NOTIFICA OS INTERESSADOS
        */




       $input = [
           'empresaId' => 2,
           'licencaId' => 2,
           'dataPagamentoBanco' => '2023-07-23',
           'numeroOperacaoBancaria' => '6176797',
           'formaPagamentoId' => 3,
           'contaMovimentadaId' => 3,
           'comprovativoBancario' => null,
           'observacao' => 'boa fatura'
       ];
        DB::beginTransaction();
        $pagarLicenca = new PagarLicenca(new DatabaseRepositoryFactory());
        $output = $pagarLicenca->execute(new Request($input));
        $this->assertTrue(true);
        DB::rollBack();

    }
}
