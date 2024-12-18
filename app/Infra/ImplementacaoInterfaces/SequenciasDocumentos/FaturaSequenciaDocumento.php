<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class FaturaSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $FATURA;
    private FaturaRepository $faturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
        $this->FATURA = EnumTipoDocumento::$FATURA;

    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->FATURA,'FATURA', $this->mostrarSerieDocumento());
        return $this->faturaRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }

    public function sequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->FATURA,'FATURA', $this->mostrarSerieDocumento());
        return $this->faturaRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
