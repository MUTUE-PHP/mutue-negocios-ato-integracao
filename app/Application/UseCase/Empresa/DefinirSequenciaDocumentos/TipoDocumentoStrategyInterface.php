<?php

namespace App\Application\UseCase\Empresa\DefinirSequenciaDocumentos;

interface TipoDocumentoStrategyInterface
{
    public function existeSequencia($tipoDocumento);
    public function sequenciaMenorExistentes($tipoDocumento);
}
