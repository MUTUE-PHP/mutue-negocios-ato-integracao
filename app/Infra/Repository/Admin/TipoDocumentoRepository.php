<?php

namespace App\Infra\Repository\Admin;

use App\Models\empresa\TipoDocumento;

class TipoDocumentoRepository
{
    public function getTiposDocumentos(){
        return TipoDocumento::get();
    }

}
