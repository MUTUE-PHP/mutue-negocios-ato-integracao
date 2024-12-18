<?php

namespace App\Domain\Entity\Empresa\Estoque;

class AtualizarEstoque
{
    private $produtoId;
    private $quantidadeAnterior;
    private $quantidadeNova;
    private $armazemId;
    private $descricao;
    private $centroCustoId;

    /**
     * @param $produtoId
     * @param $quantidadeAnterior
     * @param $quantidadeNova
     * @param $armazemId
     * @param $descricao
     * @param $centroCustoId
     */
    public function __construct($produtoId, $quantidadeAnterior, $quantidadeNova, $armazemId, $descricao, $centroCustoId)
    {
        $this->produtoId = $produtoId;
        $this->quantidadeAnterior = $quantidadeAnterior;
        $this->quantidadeNova = $quantidadeNova;
        $this->armazemId = $armazemId;
        $this->descricao = $descricao;
        $this->centroCustoId = $centroCustoId;
    }

    /**
     * @return mixed
     */
    public function getProdutoId()
    {
        return $this->produtoId;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeAnterior()
    {
        return $this->quantidadeAnterior;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeNova()
    {
        return $this->quantidadeNova;
    }

    /**
     * @return mixed
     */
    public function getArmazemId()
    {
        return $this->armazemId;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @return mixed
     */
    public function getCentroCustoId()
    {
        return $this->centroCustoId;
    }






}
