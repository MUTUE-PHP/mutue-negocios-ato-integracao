<?php

namespace App\Domain\Entity\Empresa\EntradaProduto;

class ItemEntradaProduto
{

    private $quantidade;
    private $produtoId;
    private $nomeProduto;
    private $precoVenda;
    private $precoCompra;
    private $descontoValor;
    private $descontoPercentagem;
    private $taxaIva;

    /**
     * @param $quantidade
     * @param $produtoId
     * @param $nomeProduto
     * @param $precoCompra
     * @param $descontoPercentagem
     * @param $descontoValor
     * @param $taxaIva
     */
    public function __construct($quantidade, $produtoId, $nomeProduto, $precoVenda, $precoCompra, $descontoPercentagem, $descontoValor, $taxaIva)
    {
        $this->quantidade = $quantidade?(int) $quantidade:1;
        $this->produtoId = $produtoId;
        $this->nomeProduto = $nomeProduto;
        $this->precoCompra = $precoCompra?(double)$precoCompra:0;
        $this->precoVenda = $precoVenda?(double)$precoVenda:0;
        $this->descontoPercentagem = $descontoPercentagem?(double)$descontoPercentagem:0;;;
        $this->descontoValor = $descontoValor?(double)$descontoValor:0;;
        $this->taxaIva = (int)$taxaIva;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @return mixed
     */
    public function getNomeProduto(){
        return $this->nomeProduto;
    }
    public function getProdutoId()
    {
        return $this->produtoId;
    }
    public function getTaxaIva(){
        return $this->taxaIva;
    }

    /**
     * @return mixed
     */
    public function getPrecoCompra()
    {
        return $this->precoCompra;
    }

    public function getPrecoVenda()
    {
        return $this->precoVenda;
    }
    public function getDescontoValor(){
        return $this->descontoValor;
    }
    public function getDescontoPercentagem(){
        return $this->descontoPercentagem;
    }
    public function getLucroUnitario(){
        return ($this->precoVenda * $this->quantidade) - $this->precoCompra;
    }

    public function getTotalCompra()
    {
        return $this->precoCompra * $this->quantidade;
    }
    public function getTotalSemImposto(){
        return $this->precoCompra * $this->quantidade;
    }

    public function getTotalVenda()
    {
        return $this->precoVenda * $this->quantidade;
    }
    public function getTotalTaxaIva()
    {
        return ($this->precoVenda * $this->quantidade * $this->taxaIva) / 100;
    }


    public function getTotalLucro()
    {
        return $this->getTotalVenda() - $this->getTotalCompra();
    }

    public function getTotalDesconto()
    {
        if ($this->descontoPercentagem > 100) throw new \Exception("Desconto por % vai até 100%");
        if ($this->descontoPercentagem > 0) return ($this->getTotalCompra() * $this->descontoPercentagem) / 100;
        if ($this->descontoValor > 0) return $this->descontoValor;
        return 0;
    }
}
