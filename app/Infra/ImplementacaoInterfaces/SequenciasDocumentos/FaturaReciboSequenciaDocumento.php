<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\FaturaReciboRepository;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class FaturaReciboSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $FATURA_RECIBO;
    private FaturaReciboRepository $faturaReciboRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->faturaReciboRepository = $repositoryFactory->createFaturaReciboRepository();
        $this->FATURA_RECIBO = EnumTipoDocumento::$FATURA_RECIBO;

    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->FATURA_RECIBO,'FATURA RECIBO', $this->mostrarSerieDocumento());
        return $this->faturaReciboRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }

    public function sequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->FATURA_RECIBO,'FATURA RECIBO', $this->mostrarSerieDocumento());
        return $this->faturaReciboRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
