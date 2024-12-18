<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\Pais as PaisDatabase;

class PaisRepository
{
    public function getPaises(){
        return PaisDatabase::get();
    }

}
