<?php

namespace App\Infra\Repository\Empresa;

use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Enums\EnumTipoDocumento;
use App\Models\empresa\Factura as FaturaDatabase;
use Carbon\Carbon;

class FaturaReciboRepository
{
    public function existeEstaSequencia(SequenciaDocumento $sequenciaDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return FaturaDatabase ::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracaoFactura', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaFactura', $sequenciaDocumento->getSequencia())
            ->where('tipo_documento', EnumTipoDocumento::$FATURA_RECIBO)
            ->first();
    }
    public function sequenciaMenorExistentes(SequenciaDocumento $sequenciaDocumento){
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        $data = FaturaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracaoFactura', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaFactura', '>',$sequenciaDocumento->getSequencia())
            ->where('tipo_documento', EnumTipoDocumento::$FATURA_RECIBO)
            ->first();
    }

}
