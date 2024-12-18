<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\NotaCreditoFaturaRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class NotaCreditoAnulacaoFaturaSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $NOTA_CREDITO_ANULACAO_FATURAS;
    private NotaCreditoFaturaRepository $notaCreditoRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->notaCreditoRepository = $repositoryFactory->createNotaCreditoFaturaRepository();
        $this->NOTA_CREDITO_ANULACAO_FATURAS = EnumTipoDocumento::$NOTA_CREDITO_ANULACAO_FATURAS;

    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->NOTA_CREDITO_ANULACAO_FATURAS,'NOTA CREDITO/ANULAÇÃO FATURA', $this->mostrarSerieDocumento());
        return $this->notaCreditoRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }

    public function sequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->NOTA_CREDITO_ANULACAO_FATURAS,'NOTA CREDITO/ANULAÇÃO FATURA', $this->mostrarSerieDocumento());
        return $this->notaCreditoRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
