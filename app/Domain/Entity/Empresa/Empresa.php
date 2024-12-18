<?php

namespace App\Domain\Entity\Empresa;

class Empresa
{
    private $nome;
    private $telefone1;
    private $telefone2;
    private $telefone3;
    private $pessoaDeContato;
    private $endereco;
    private $website;
    private $regimeId;
    private $nif;
    private $email;
    private $logotipo;
    private $paisId;
    private $tipoClienteId;
    private $canalId;
    private $statusId;
    private $provinciaId;
    private $vendaOnline;
    private $fileAlvara;
    private $fileNif;
    private $referencia;

    public function __construct($nome, $telefone1, $telefone2 = null, $telefone3 = null, $pessoaDeContato, $endereco, $website, $regimeId, $nif, $email, $logotipo, $paisId, $tipoClienteId, $canalId, $statusId = 1, $provinciaId = 1, $vendaOnline = 'N', $fileAlvara = null, $fileNif = null, $referencia)
    {
        $this->nome = $nome;
        $this->telefone1 = $telefone1;
        $this->telefone2 = $telefone2;
        $this->telefone3 = $telefone3;
        $this->pessoaDeContato = $pessoaDeContato;
        $this->endereco = $endereco;
        $this->website = $website;
        $this->regimeId = $regimeId;
        $this->nif = $nif;
        $this->email = $email;
        $this->logotipo = $logotipo;
        $this->paisId = $paisId;
        $this->tipoClienteId = $tipoClienteId;
        $this->canalId = $canalId;
        $this->statusId = $statusId;
        $this->provinciaId = $provinciaId;
        $this->vendaOnline = $vendaOnline;
        $this->fileAlvara = $fileAlvara;
        $this->fileNif = $fileNif;
        $this->referencia = $referencia;
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
    public function getTelefone1()
    {
        return $this->telefone1;
    }

    /**
     * @return mixed|null
     */
    public function getTelefone2()
    {
        return $this->telefone2;
    }

    /**
     * @return mixed|null
     */
    public function getTelefone3()
    {
        return $this->telefone3;
    }

    /**
     * @return mixed
     */
    public function getPessoaDeContato()
    {
        return $this->pessoaDeContato;
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
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return mixed
     */
    public function getRegimeId()
    {
        return $this->regimeId;
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
    public function getEmail()
    {
        return $this->email;
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
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * @return mixed
     */
    public function getTipoClienteId()
    {
        return $this->tipoClienteId;
    }

    /**
     * @return mixed
     */
    public function getCanalId()
    {
        return $this->canalId;
    }

    /**
     * @return int|mixed
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @return int|mixed
     */
    public function getProvinciaId()
    {
        return $this->provinciaId;
    }

    /**
     * @return mixed|string
     */
    public function getVendaOnline()
    {
        return $this->vendaOnline;
    }

    /**
     * @return mixed|null
     */
    public function getFileAlvara()
    {
        return $this->fileAlvara;
    }

    /**
     * @return mixed|null
     */
    public function getFileNif()
    {
        return $this->fileNif;
    }

    /**
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }
}
