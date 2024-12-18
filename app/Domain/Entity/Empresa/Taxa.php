<?php

namespace App\Domain\Entity\Empresa;


class Taxa{
    private $designacao;
    private $taxa;
    private $desconto;

    public function __construct($designacao, $taxa, $desconto)
    {
        $this->designacao = $designacao;
        $this->taxa = $taxa;
        $this->desconto = $desconto;
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
    public function getTaxa()
    {
        return $this->taxa;
    }
    public function getDesconto()
    {
        return $this->desconto;
    }


}
