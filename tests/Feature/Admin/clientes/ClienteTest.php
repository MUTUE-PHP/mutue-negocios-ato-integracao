<?php

namespace Admin\clientes;

use App\Application\UseCase\Admin\Cliente\GetClientes;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    public function testDeveListarTodosCliente(){
        $clientes = new GetClientes(new DatabaseRepositoryFactory());
        $clienteData = $clientes->execute();
        $this->assertNotNull(count($clienteData));
    }

}
