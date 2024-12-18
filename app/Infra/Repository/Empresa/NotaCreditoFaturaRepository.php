<?php

namespace App\Infra\Repository\Empresa;

use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Models\empresa\NotaCredito;
use App\Models\empresa\Recibos as ReciboDatabase;
use Carbon\Carbon;

class NotaCreditoFaturaRepository
{
    public function existeEstaSequencia(SequenciaDocumento $sequenciaDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return NotaCredito::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('facturaId','!=', null)
            ->where('numeracaoDocumento', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaNotaCredito', $sequenciaDocumento->getSequencia())
            ->first();
    }
    public function sequenciaMenorExistentes(SequenciaDocumento $sequenciaDocumento){
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return NotaCredito::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('facturaId','!=', null)
            ->where('numeracaoDocumento', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaNotaCredito', '>',$sequenciaDocumento->getSequencia())
            ->first();
    }
}
