<?php

namespace Admin\licencas;
use App\Application\UseCase\Admin\Licenca\GetLicencas;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetLicencaTest extends TestCase
{

    public function testDeveListarTodasLicencas(){
        $licencas = new GetLicencas(new DatabaseRepositoryFactory());
        $licencasData = $licencas->execute();
        $this->assertNotNull(count($licencasData));
    }

}
