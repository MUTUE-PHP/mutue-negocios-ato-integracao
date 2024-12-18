<?php

namespace App\Infra\Repository\VendasOnline;

use App\Models\empresa\TiposEntrega as TiposEntregaDatabase;

class TipoEntregaRepository
{

    public function getTiposEntrega(){
        return TiposEntregaDatabase::where('status_id', 1)->get();
    }

}
