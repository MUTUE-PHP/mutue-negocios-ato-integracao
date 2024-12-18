<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\TipoDocumento as TipoDocumentoDatabase;

class TipoDocumentoRepository
{
    public function getTipoDocumentoFaturacao(){
        return TipoDocumentoDatabase::whereIn('id', [1, 2, 3])->get();
    }

}
