<?php

namespace Admin\FormaPagamentos;

use App\Application\UseCase\Admin\FormasPagamento\GetFormasPagamento;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormaPagamentoTest extends TestCase
{
    public function testDeveListarTodasFormasPagamento()
    {
        $formasPagamento = new GetFormasPagamento(new DatabaseRepositoryFactory());
        $formasPagamentoData = $formasPagamento->execute();
        $this->assertNotNull(count($formasPagamentoData));
    }

}
