<?php

namespace App\Domain\Entity\Admin;

class Empresa
{
    private $id;
    private $nome;
    private $endereco;
    private $nif;
    private $email;
    private $telefone;

    /**
     * @param $nome
     * @param $endereco
     * @param $nif
     * @param $email
     */
    public function __construct($id, $nome, $endereco, $nif, $email, $telefone)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->nif = $nif;
        $this->email = $email;
        $this->telefone = $telefone;
    }

    public function getId()
    {
        return $this->id;
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
    public function getEmail()
    {
        return $this->email;
    }
    public function getTelefone()
    {
        return $this->telefone;
    }
}
