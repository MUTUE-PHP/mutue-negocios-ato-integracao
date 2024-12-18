<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\NotaCreditoFaturaRepository;
use App\Infra\Repository\Empresa\NotaCreditoReciboRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class NotaCreditoAnulacaoReciboSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $NOTA_CREDITO_ANULACAO_RECIBOS;
    private NotaCreditoReciboRepository $notaCreditoReciboRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->notaCreditoReciboRepository = $repositoryFactory->createNotaCreditoReciboRepository();
        $this->NOTA_CREDITO_ANULACAO_RECIBOS = EnumTipoDocumento::$NOTA_CREDITO_ANULACAO_RECIBOS;

    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->NOTA_CREDITO_ANULACAO_RECIBOS,'NOTA CREDITO/ANULAÇÃO RECIBO', $this->mostrarSerieDocumento());
        return $this->notaCreditoReciboRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }

    public function sequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->NOTA_CREDITO_ANULACAO_RECIBOS,'NOTA CREDITO/ANULAÇÃO RECIBO', $this->mostrarSerieDocumento());
        return $this->notaCreditoReciboRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
