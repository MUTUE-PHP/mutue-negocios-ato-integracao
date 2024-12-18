<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\MotivoIsencao as MotivosIsencaoDatabase;

class MotivosIsencaoRepository
{
    public function getMotivosIsencao(){
        return MotivosIsencaoDatabase::where('codigo','!=', 7)
            ->where('codigo', '!=', 9)
            ->get();
    }
}
