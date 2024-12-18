<?php

namespace App\Domain\Entity\VendasOnline;

class EnderecoEntregaVendasOnline
{

    private $nomeUserEntrega;
    private $apelidoUserEntrega;
    private $endereco;
    private $pontoReferencia;
    private $telefoneUser;
    private $provincia;
    private $comunaId;
    private $emailEntrega;
    private $observacao;

    public function __construct($nomeUserEntrega, $apelidoUserEntrega,  $endereco, $pontoReferencia, $telefoneUser, $provincia, $comunaId, $emailEntrega, $observacao)
    {
        $this->nomeUserEntrega = $nomeUserEntrega;
        $this->apelidoUserEntrega = $apelidoUserEntrega;
        $this->endereco = $endereco;
        $this->pontoReferencia = $pontoReferencia;
        $this->telefoneUser = $telefoneUser;
        $this->provincia = $provincia;
        $this->comunaId = $comunaId;
        $this->observacao = $observacao?? null;
        $this->emailEntrega = $emailEntrega?? null;
    }


    public function getNomeUserEntrega()
    {
        return $this->nomeUserEntrega;
    }
    public function getApelidoUserEntrega()
    {
        return $this->apelidoUserEntrega;
    }
    /**
     * @return mixed
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @return mixed
     */
    public function getPontoReferencia()
    {
        return $this->pontoReferencia;
    }

    /**
     * @return mixed
     */
    public function getTelefoneUser()
    {
        return $this->telefoneUser;
    }
    /**
     * @return mixed
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * @return mixed
     */
    public function getComunaId()
    {
        return $this->comunaId;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }
    public function getEmail()
    {
        return $this->emailEntrega;
    }
    public function __toString()
    {
        return '';
    }
}
