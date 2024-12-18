<?php

namespace App\Domain\Entity\Empresa\Faturacao;

class FaturaItemEmitida
{

    private $nomeProduto;
    private $precoVendaProduto;
    private $produtoCartaGarantia;
    private $tempoGarantiaProduto;
    private $precoCompraProduto;
    private $quantidadeProduto;
    private $descontoTaxa;
    private $descontoProduto;
    private $quantidadeAnteriorProduto;
    private $incidenciaProduto;
    private $retencaoProduto;
    private $taxaProduto;
    private $ivaProduto;
    private $totalPrecoProduto;
    private $produtoId;
    private $faturaId;
    private $armazemId;
    private $isEstocavel;
    private $quantidadeStock;
    private $quantidadeMinima;
    private $quantidadeCritica;

    /**
     * @param $nomeProduto
     * @param $precoVendaProduto
     * @param $precoCompraProduto
     * @param $quantidadeProduto
     * @param $descontoProduto
     * @param $quantidadeAnteriorProduto
     * @param $incidenciaProduto
     * @param $retencaoProduto
     * @param $taxaProduto
     * @param $ivaProduto
     * @param $totalPrecoProduto
     * @param $produtoId
     * @param $faturaId
     * @param $armazemId
     * @param $isEstocavel;
     * @param $quantidadeStock;
     *
     */
    public function __construct(
        $nomeProduto,
        $precoVendaProduto,
        $produtoCartaGarantia,
        $tempoGarantiaProduto,
        $precoCompraProduto,
        $quantidadeProduto,
        $descontoTaxa,
        $descontoProduto,
        $quantidadeAnteriorProduto,
        $incidenciaProduto,
        $retencaoProduto,
        $taxaProduto,
        $ivaProduto,
        $totalPrecoProduto,
        $produtoId,
        $faturaId,
        $armazemId,
        $isEstocavel,
        $quantidadeStock,
        $quantidadeMinima,
        $quantidadeCritica
    )
    {
        $this->nomeProduto = $nomeProduto;
        $this->precoVendaProduto = $precoVendaProduto;
        $this->produtoCartaGarantia = $produtoCartaGarantia;
        $this->tempoGarantiaProduto = $tempoGarantiaProduto;
        $this->precoCompraProduto = $precoCompraProduto;
        $this->descontoTaxa = $descontoTaxa;
        $this->quantidadeProduto = $quantidadeProduto;
        $this->descontoProduto = $descontoProduto;
        $this->quantidadeAnteriorProduto = $quantidadeAnteriorProduto;
        $this->incidenciaProduto = $incidenciaProduto;
        $this->retencaoProduto = $retencaoProduto;
        $this->taxaProduto = $taxaProduto;
        $this->ivaProduto = $ivaProduto;
        $this->totalPrecoProduto = $totalPrecoProduto;
        $this->produtoId = $produtoId;
        $this->faturaId = $faturaId;
        $this->armazemId = $armazemId;
        $this->isEstocavel = $isEstocavel;
        $this->quantidadeStock = $quantidadeStock;
        $this->quantidadeMinima = $quantidadeMinima;
        $this->quantidadeCritica = $quantidadeCritica;
    }

    /**
     * @return mixed
     */
    public function getNomeProduto()
    {
        return $this->nomeProduto;
    }

    /**
     * @return mixed
     */
    public function getPrecoVendaProduto()
    {
        return $this->precoVendaProduto;
    }
    public function getProdutoCartaGarantia(){
        return $this->produtoCartaGarantia;
    }
    public function getTempoGarantiaProduto(){
        return $this->tempoGarantiaProduto;
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
    public function getQuantidadeProduto()
    {
        return $this->quantidadeProduto;
    }

    public function getDescontoTaxa()
    {
        return $this->descontoTaxa;
    }

    /**
     * @return mixed
     */
    public function getDescontoProduto()
    {
        return $this->descontoProduto;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeAnteriorProduto()
    {
        return $this->quantidadeAnteriorProduto;
    }

    /**
     * @return mixed
     */
    public function getIncidenciaProduto()
    {
        return $this->incidenciaProduto;
    }

    /**
     * @return mixed
     */
    public function getRetencaoProduto()
    {
        return $this->retencaoProduto;
    }

    /**
     * @return mixed
     */
    public function getTaxaProduto()
    {
        return $this->taxaProduto;
    }

    /**
     * @return mixed
     */
    public function getIvaProduto()
    {
        return $this->ivaProduto;
    }

    /**
     * @return mixed
     */
    public function getTotalPrecoProduto()
    {
        return $this->totalPrecoProduto;
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
    public function getFaturaId()
    {
        return $this->faturaId;
    }
    public function getArmazemId()
    {
        return $this->armazemId;
    }
    public function getIsEstocavel()
    {
        return $this->isEstocavel;
    }
    public function getQuantidadeStock()
    {
        return $this->quantidadeStock;
    }
    public function getQuantidadeMinima()
    {
        return $this->quantidadeMinima;
    }
    public function getQuantidadeCritica()
    {
        return $this->quantidadeCritica;
    }
}
