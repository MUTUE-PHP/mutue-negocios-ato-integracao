<?php

namespace App\Domain\Entity\Empresa\Faturacao;

class FaturaItem
{

    private $produtoId;
    private $armazemId;
    private $nomeProduto;
    private $codigoProduto;
    private $produtoCartaGarantia;
    private $tempoGarantiaProduto;
    private $tipoGarantia;
    private $precoVendaProduto;
    private $pvp;
    private $precoCompraProduto;
    private $quantidadeStock;
    private $isEstocavel;
    private $quantidadeMinima;
    private $quantidadeCritica;
    private $taxaIva;
    private $iva;
    private $quantidade;
    private $desconto;
    private $descontoGeral;

    public function __construct($produtoId, $armazemId, $nomeProduto, $codigoProduto, $produtoCartaGarantia,$tempoGarantiaProduto, $tipoGarantia, $precoVendaProduto, $pvp, $precoCompraProduto, $quantidadeStock, $isEstocavel, $quantidadeMinima, $quantidadeCritica, $taxaIva, $iva, $quantidade, $descontoItem, $descontoGeral)
    {
        $this->produtoId = $produtoId;
        $this->armazemId = $armazemId;
        $this->nomeProduto = $nomeProduto;
        $this->codigoProduto = $codigoProduto;
        $this->produtoCartaGarantia = $produtoCartaGarantia;
        $this->tempoGarantiaProduto = $tempoGarantiaProduto;
        $this->tipoGarantia = $tipoGarantia;
        $this->precoVendaProduto = $precoVendaProduto;
        $this->pvp = $pvp;
        $this->precoCompraProduto = $precoCompraProduto;
        $this->quantidadeStock = $quantidadeStock;
        $this->isEstocavel = $isEstocavel;
        $this->quantidadeMinima = $quantidadeMinima;
        $this->quantidadeCritica = $quantidadeCritica;
        $this->taxaIva = $taxaIva;
        $this->iva = $iva;
        $this->quantidade = $quantidade;
        $this->desconto = $descontoItem??0;
        $this->descontoGeral = $descontoGeral??0;
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
    public function getArmazemId()
    {
        return $this->armazemId;
    }

    /**
     * @return mixed
     */
    public function getNomeProduto()
    {
        return $this->nomeProduto;
    }
    public function getCodigoProduto()
    {
        return $this->codigoProduto;
    }
    public function getProdutoCartaGarantia(){
        return $this->produtoCartaGarantia;
    }
    public function getTempoGarantiaProduto(){
        return $this->tempoGarantiaProduto;
    }
    public function getTipoGarantia(){
        return $this->tipoGarantia;
    }

    /**
     * @return mixed
     */
    public function getPrecoVendaProduto()
    {
        return $this->precoVendaProduto;
    }
    public function getPvp()
    {
        return $this->pvp;
    }

    /**
     * @return mixed
     */
    public function getPrecoCompraProduto()
    {
        return $this->precoCompraProduto;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeStock()
    {
        return $this->quantidadeStock;
    }

    /**
     * @return mixed
     */
    public function getIsEstocavel()
    {
        return $this->isEstocavel;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidadeMinima;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeCritica()
    {
        return $this->quantidadeCritica;
    }

    /**
     * @return mixed
     */
    public function getTaxaIva()
    {
        return $this->taxaIva;
    }
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }
    public function descontoGeral(){
        return $this->descontoGeral;
    }
    /**
     * @return mixed
     */
    public function getDesconto()
    {
        return $this->desconto;
    }
    public function subTotalTaxaIva()
    {
        return ($this->subTotalIncidencia() * $this->taxaIva) / 100;
    }
    public function subTotalPrecoProduto()
    {
        return $this->precoVendaProduto * $this->quantidade ?? 1;
    }

    public function subTotalDesconto()
    {
        $desconto = $this->descontoGeral+ $this->desconto;
        if($desconto > 100){
            $desconto = 100;
        }
        return ($this->getPvp() * $this->quantidade * $desconto ?? 0) / 100;
    }

    public function subTotalIncidencia()
    {
        return ($this->precoVendaProduto * $this->quantidade);
    }

}
