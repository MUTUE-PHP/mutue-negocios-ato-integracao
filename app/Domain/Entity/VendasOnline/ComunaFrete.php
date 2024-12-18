<?php

namespace App\Domain\Entity\VendasOnline;

class ComunaFrete
{
    private $designacao;
    private $valorEntrega;
    private $municipioId;
    private $statusId;

    public function __construct($designacao, $valorEntrega, $municipioId, $statusId)
    {
        $this->designacao = $designacao;
        $this->valorEntrega = $valorEntrega;
        $this->municipioId = $municipioId;
        $this->statusId = $statusId;
    }

    /**
     * @return mixed
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }

    /**
     * @return mixed
     */
    public function getValorEntrega()
    {
        return $this->valorEntrega;
    }

    /**
     * @return mixed
     */
    public function getMunicipioId()
    {
        return $this->municipioId;
    }
    public function getStatusId()
    {
        return $this->statusId;
    }
}
