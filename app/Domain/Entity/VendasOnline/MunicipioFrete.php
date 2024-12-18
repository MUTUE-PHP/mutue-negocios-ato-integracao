<?php

namespace App\Domain\Entity\VendasOnline;

class MunicipioFrete
{
    private $designacao;
    private $valorEntrega;
    private $provinciaId;
    private $statusId;

    public function __construct($designacao, $valorEntrega, $provinciaId, $statusId)
    {
        $this->designacao = $designacao;
        $this->valorEntrega = $valorEntrega;
        $this->provinciaId = $provinciaId;
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
    public function getProvinciaId()
    {
        return $this->provinciaId;
    }
    public function getStatusId()
    {
        return $this->statusId;
    }
}
