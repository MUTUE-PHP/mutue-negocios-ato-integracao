<?php

namespace App\Domain\Entity\Empresa\FaturaAeroporto;

class FaturaItemOutroServico
{
    private $produtoId;
    private $nomeProduto;
    private $descontoItem;
    private $precoProduto;
    private $taxaIva;
    private $quantidade;
    private $cambioDia;

    public function __construct($produtoId, $nomeProduto,$descontoItem, $precoProduto, $taxaIva, $quantidade, $cambioDia)
    {
        $this->produtoId = $produtoId;
        $this->nomeProduto = $nomeProduto;
        $this->descontoItem = $descontoItem;
        $this->precoProduto = $precoProduto;
        $this->taxaIva = $taxaIva;
        $this->quantidade = $quantidade;
        $this->cambioDia = $cambioDia;
    }

    public function getProdutoId()
    {
        return $this->produtoId;
    }

    public function getNomeProduto()
    {
        return $this->nomeProduto;
    }
    public function getDesconto()
    {
        return $this->descontoItem;
    }
    public function getValorDesconto()
    {
        return $this->getTotal() * $this->getDesconto() / 100;
    }
    public function getTotalComDescontoAplicado(){
        return $this->getTotal() - $this->getValorDesconto();
    }
    public function getTotalIva(){
        return $this->getTotalComDescontoAplicado() + $this->getValorIva();
    }

    public function getPrecoProduto()
    {
        return $this->precoProduto;
    }

    public function getTaxaIva()
    {
        return $this->taxaIva;
    }

    public function getValorIva()
    {
        return ($this->getTotalComDescontoAplicado() * $this->getTaxaIva()) / 100;
    }

//    public function getTotalIva()
//    {
//        return $this->getTotal() + $this->getValorIva();
//    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function getCambioDia()
    {
        return $this->cambioDia;
    }

    public function getTotal()
    {
        if ($this->getProdutoId() == 21) { //Assistência a combustível e óleo
            return ($this->getQuantidade() / 100) * $this->getPrecoProduto() * $this->getCambioDia();
        }
        return $this->getPrecoProduto() * $this->getQuantidade() * $this->getCambioDia();
    }
}
