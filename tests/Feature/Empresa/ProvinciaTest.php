<?php

namespace Empresa;

use App\Application\UseCase\GetProvincias;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Tests\TestCase;

class ProvinciaTest extends TestCase
{

    public function testDeveListarTodasProvincias(){

        $getProvincias = new GetProvincias(new DatabaseRepositoryFactory());
        $output = $getProvincias->execute();
        $this->assertNotNull($output);
    }
}
