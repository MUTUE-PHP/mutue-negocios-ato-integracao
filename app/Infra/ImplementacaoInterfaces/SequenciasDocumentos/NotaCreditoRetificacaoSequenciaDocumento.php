<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\NotaCreditoFaturaRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class NotaCreditoRetificacaoSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $NOTA_CREDITO_RETIFICACAO;
    private NotaCreditoFaturaRepository $notaCreditoFaturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->notaCreditoFaturaRepository = $repositoryFactory->createNotaCreditoFaturaRepository();
        $this->NOTA_CREDITO_RETIFICACAO = EnumTipoDocumento::$NOTA_CREDITO_RETIFICACAO;

    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->NOTA_CREDITO_RETIFICACAO,'NOTA CREDITO/RETIFICAÇÃO', $this->mostrarSerieDocumento());
        return $this->notaCreditoFaturaRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }
    public function sequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->NOTA_CREDITO_RETIFICACAO,'NOTA CREDITO/RETIFICAÇÃO', $this->mostrarSerieDocumento());
        return $this->notaCreditoFaturaRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
