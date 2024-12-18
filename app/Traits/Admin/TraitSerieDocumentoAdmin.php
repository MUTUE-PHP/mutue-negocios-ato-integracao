<?php

namespace App\Traits\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait TraitSerieDocumentoAdmin
{
    public function mostrarSerieDocumento()
    {
        $empresa = DB::connection('mysql')->table('empresas')
            ->where('id',1)
            ->first();
        return mb_strtoupper(substr(Str::slug($empresa->nome), 0, 3));
    }

}
