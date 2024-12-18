<?php

namespace App\Infra\ImplementacaoInterfaces\SequenciasDocumentos;

use App\Application\UseCase\Empresa\DefinirSequenciaDocumentos\TipoDocumentoStrategyInterface;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Enums\EnumTipoDocumento;
use App\Infra\Repository\Empresa\ReciboRepository;
use App\Repositories\Empresa\TraitSerieDocumento;

class ReciboSequenciaDocumento implements TipoDocumentoStrategyInterface
{
    use TraitSerieDocumento;
    private $RECIBO;
    private ReciboRepository $reciboRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->reciboRepository = $repositoryFactory->createReciboRepository();
        $this->RECIBO = EnumTipoDocumento::$RECIBO;
    }
    public function existeSequencia($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->RECIBO,'RECIBOS', $this->mostrarSerieDocumento());
        return $this->reciboRepository->existeEstaSequencia($tipoSequenciaDocumento);
    }

    public function SequenciaMenorExistentes($numeroSequencia)
    {
        $tipoSequenciaDocumento = new SequenciaDocumento($numeroSequencia, $this->RECIBO,'RECIBOS', $this->mostrarSerieDocumento());
        return $this->reciboRepository->sequenciaMenorExistentes($tipoSequenciaDocumento);
    }
}
