<?php

namespace Admin;

use App\Application\UseCase\Admin\ResetarSenhaCliente;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ResetarSenhaDoClienteTest extends TestCase
{
    public function testDeveResetarSenhaDoCliente(){
        $input = [
            'clienteId' => 2,
            'novaSenha' => '123'
        ];
        DB::beginTransaction();
        $resetarSenhaCliente = new ResetarSenhaCliente(new DatabaseRepositoryFactory());
        $output = $resetarSenhaCliente->execute(new Request($input));
        $this->assertSame(1, $output);
        DB::rollBack();
    }
}
