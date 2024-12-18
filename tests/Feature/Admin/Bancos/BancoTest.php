<?php

namespace Admin\Bancos;

use App\Application\UseCase\Admin\Banco\GetBancos;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BancoTest extends TestCase
{

    public function testDeveListarTodosBancos(){
        $bancos = new GetBancos(new DatabaseRepositoryFactory());
        $bancoData = $bancos->execute();
        $this->assertNotNull(count($bancoData));
    }

}
