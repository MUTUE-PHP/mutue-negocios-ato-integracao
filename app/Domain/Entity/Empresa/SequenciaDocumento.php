<?php

namespace App\Domain\Entity\Empresa;

class SequenciaDocumento
{
    private $sequencia;
    private $tipoDocumento;
    private $tipoDocumentoNome;
    private $serieDocumento;

    /**
     * @param $sequencia
     * @param $tipoDocumento
     * @param $serieDocumento
     */
    public function __construct($sequencia, $tipoDocumento,$tipoDocumentoNome, $serieDocumento)
    {
        $this->sequencia = $sequencia;
        $this->tipoDocumento = $tipoDocumento;
        $this->tipoDocumentoNome = $tipoDocumentoNome;
        $this->serieDocumento = $serieDocumento;
    }

    /**
     * @return mixed
     */
    public function getSequencia()
    {
        return $this->sequencia;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }
    public function getTipoDocumentoNome()
    {
        return $this->tipoDocumentoNome;
    }

    /**
     * @return mixed
     */
    public function getSerieDocumento()
    {
        return $this->serieDocumento;
    }
}
