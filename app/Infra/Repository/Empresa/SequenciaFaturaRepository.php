<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\SequenciaDocumentoDatabase;

class SequenciaFaturaRepository
{
    public function getUltimaSequenciaFatura($tipoDocumento){
        return SequenciaDocumentoDatabase::where('tipo_documento', $tipoDocumento)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('id', 'DESC')
            ->first();
    }
}
