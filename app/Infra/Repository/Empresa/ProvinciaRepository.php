<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Provincia as ProvinciaDatabase;

class ProvinciaRepository
{
    public function getProvincias()
    {
        return ProvinciaDatabase::get();
    }

}
