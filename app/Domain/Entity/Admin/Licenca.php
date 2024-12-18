<?php

namespace App\Domain\Entity\Admin;

class Licenca
{
    private $id;
    private $designacao;
    private $valor;
    private $limiteUsuarios;
    private $taxaIva;

    /**
     * @param $designacao
     * @param $valor
     * @param $limiteUsuarios
     */
    public function __construct($id, $designacao, $valor, $limiteUsuarios, $taxaIva)
    {
        $this->id = $id;
        $this->designacao = $designacao;
        $this->valor = $valor ?? 0;
        $this->limiteUsuarios = $limiteUsuarios ?? 1;
        $this->taxaIva = $taxaIva ?? 0;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }

    /**
     * @return int
     */
    public function getValor(): int
    {
        return $this->valor;
    }

    /**
     * @return int
     */
    public function getLimiteUsuarios(): int
    {
        return $this->limiteUsuarios;
    }

    public function getValorTaxaIva()
    {
        return ($this->valor * $this->taxaIva) / 100;
    }
}
