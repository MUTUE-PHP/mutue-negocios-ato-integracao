<?php

namespace App\Domain\Entity\Empresa\CentrosDeCusto;

class CentroCusto
{

    private $nome;
    private $endereco;
    private $nif;
    private $cidade;
    private $logotipo;
    private $email;
    private $website;
    private $telefone;
    private $pessoaContato;
    private $fileAlvara;
    private $fileNif;
    private $statusId;

    /**
     * @param $nome
     * @param $endereco
     * @param $nif
     * @param $cidade
     * @param $logotipo
     * @param $email
     * @param $website
     * @param $telefone
     * @param $pessoaContato
     * @param $fileAlvara
     * @param $fileNif
     */
    public function __construct($nome, $endereco, $nif, $cidade, $logotipo, $email, $website, $telefone, $pessoaContato, $fileAlvara, $fileNif, $statusId)
    {
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->nif = $nif;
        $this->cidade = $cidade;
        $this->logotipo = $logotipo;
        $this->email = $email;
        $this->website = $website;
        $this->telefone = $telefone;
        $this->pessoaContato = $pessoaContato??null;
        $this->fileAlvara = $fileAlvara??null;
        $this->fileNif = $fileNif??null;
        $this->statusId = $statusId??1;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
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
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @return mixed
     */
    public function getLogotipo()
    {
        return $this->logotipo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return mixed
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @return mixed
     */
    public function getPessoaContato()
    {
        return $this->pessoaContato;
    }

    /**
     * @return mixed
     */
    public function getFileAlvara()
    {
        return $this->fileAlvara;
    }

    /**
     * @return mixed
     */
    public function getFileNif()
    {
        return $this->fileNif;
    }
    public function getStatusId()
    {
        return $this->statusId;
    }
}
