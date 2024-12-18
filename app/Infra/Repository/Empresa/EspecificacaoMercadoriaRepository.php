<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\EspecificacaoMercadoria;

class EspecificacaoMercadoriaRepository
{

    public function getEspecificacaoMercadoriaById($especificacaoMercadoriaId){
        return EspecificacaoMercadoria::where('id', $especificacaoMercadoriaId)->first();
    }

}
