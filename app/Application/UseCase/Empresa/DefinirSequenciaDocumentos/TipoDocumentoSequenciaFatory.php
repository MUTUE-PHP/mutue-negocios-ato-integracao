<?php

namespace App\Application\UseCase\Empresa\DefinirSequenciaDocumentos;

use App\Enums\EnumTipoDocumento;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\FaturaProformaSequenciaDocumento;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\FaturaReciboSequenciaDocumento;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\FaturaSequenciaDocumento;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\NotaCreditoAnulacaoFaturaSequenciaDocumento;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\NotaCreditoAnulacaoReciboSequenciaDocumento;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\NotaCreditoRetificacaoSequenciaDocumento;
use App\Infra\ImplementacaoInterfaces\SequenciasDocumentos\ReciboSequenciaDocumento;

class TipoDocumentoSequenciaFatory
{
    public function execute($tipoDocumento)
    {
        switch ($tipoDocumento) {
            case EnumTipoDocumento::$FATURA_RECIBO:
                return new FaturaReciboSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            case EnumTipoDocumento::$FATURA:
                return new FaturaSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            case EnumTipoDocumento::$FATURA_PROFORMA:
                return new FaturaProformaSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            case EnumTipoDocumento::$RECIBO:
                return new ReciboSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            case EnumTipoDocumento::$NOTA_CREDITO_ANULACAO_FATURAS:
                return new NotaCreditoAnulacaoFaturaSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            case EnumTipoDocumento::$NOTA_CREDITO_ANULACAO_RECIBOS:
                return new NotaCreditoAnulacaoReciboSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            case EnumTipoDocumento::$NOTA_CREDITO_RETIFICACAO:
                return new NotaCreditoRetificacaoSequenciaDocumento(new DatabaseRepositoryFactory());
                break;
            default:
                throw new \Error("Tipo documento não existe");
                break;
        }

    }
}
