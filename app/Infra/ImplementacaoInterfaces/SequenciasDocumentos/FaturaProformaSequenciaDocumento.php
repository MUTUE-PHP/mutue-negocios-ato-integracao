<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\FaturaProformaRepository;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class FaturaProformaSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $FATURA_PROFORMA;
    private FaturaProformaRepository $faturaProformaRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->faturaProformaRepository = $repositoryFactory->createFaturaProformaRepository();
        $this->FATURA_PROFORMA = EnumTipoDocumento::$FATURA_PROFORMA;
    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->FATURA_PROFORMA,'FATURA PROFORMA', $this->mostrarSerieDocumento());
        return $this->faturaProformaRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }
    public function sequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->FATURA_PROFORMA,'FATURA PROFORMA', $this->mostrarSerieDocumento());
        return $this->faturaProformaRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
