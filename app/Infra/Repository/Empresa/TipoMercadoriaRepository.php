<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\TipoMercadoria;

use App\Models\empresa\Armazen as ArmazemDatabase;


class TipoMercadoriaRepository
{
    public function getTipoMercadorias()
    {
        return TipoMercadoria::get();
    }
    public function salvar($mercadoria){
        return TipoMercadoria::create([
            'designacao' => $mercadoria->designacao,
            'valor' => $mercadoria->valor,
            'statuId' => $mercadoria->statuId,
        ]);

    }
    public function getTipoMercadoria($mercadoriaId){
        return TipoMercadoria::where('id', $mercadoriaId)->first();
    }


}
