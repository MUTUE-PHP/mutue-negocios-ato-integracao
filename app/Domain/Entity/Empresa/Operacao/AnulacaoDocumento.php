<?php

namespace App\Domain\Entity\Empresa\Operacao;

class AnulacaoDocumento
{
    private $facturaId;
    private $reciboId;
    private $numDoc;
    private $hash;
    private $hashTexto;
    private $numSequencia;
    private $descricao;

    public function __construct($facturaId, $reciboId, $numDoc, $hash, $hashTexto, $numSequencia, $descricao)
    {
        $this->facturaId = $facturaId;
        $this->reciboId = $reciboId;
        $this->numDoc = $numDoc;
        $this->hash = $hash;
        $this->hashTexto = $hashTexto;
        $this->numSequencia = $numSequencia;
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getFacturaId()
    {
        return $this->facturaId;
    }

    /**
     * @return mixed
     */
    public function getReciboId()
    {
        return $this->reciboId;
    }

    /**
     * @return mixed
     */
    public function getNumDoc()
    {
        return $this->numDoc;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getHashTexto()
    {
        return $this->hashTexto;
    }

    /**
     * @return mixed
     */
    public function getNumSequencia()
    {
        return $this->numSequencia;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }
}
