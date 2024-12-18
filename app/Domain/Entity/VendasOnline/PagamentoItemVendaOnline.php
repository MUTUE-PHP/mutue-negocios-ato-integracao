<?php

namespace App\Domain\Entity\VendasOnline;

class PagamentoItemVendaOnline
{
    private $produtoId;
    private $nomeProduto;
    private $precoVendaProduto;
    private $taxa;
    private $quantidade;

    public function __construct($produtoId, $nomeProduto, $precoVendaProduto, $taxa, $quantidade)
    {
        $this->produtoId = $produtoId;
        $this->nomeProduto = $nomeProduto;
        $this->precoVendaProduto = $precoVendaProduto;
        $this->taxa = $taxa;
        $this->quantidade = $quantidade;
    }
    public function getSubtotal(){
        return $this->getSubTotalSemIva() + $this->getTaxaValor();
    }
    public function getSubTotalSemIva(){
        return ($this->precoVendaProduto * $this->quantidade);
    }
    public function getTaxa(){
        return $this->taxa;
    }
    public function getTaxaValor(){
        return $this->getSubTotalSemIva() * ($this->getTaxa() / 100);
    }
    public function getProdutoId()
    {
        return $this->produtoId;
    }
    public function getNomeProduto()
    {
        return $this->nomeProduto;
    }
    public function getPrecoVendaProduto()
    {
        return $this->precoVendaProduto;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }
}
