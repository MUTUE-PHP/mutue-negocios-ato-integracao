<?php

namespace App\Domain\Entity\Empresa\Produtos;

use Keygen\Keygen;

class Produto
{
    private $designacao;
    private $precoVenda;
    private $precoCompra;
    private $categoriaId;
    private $tipoServicoId;
    private $orderCategoria1;
    private $orderCategoria2;
    private $orderCategoria3;
    private $unidadeMedidaId;
    private $armazemId;
    private $fabricanteId;
    private $statusId;
    private $taxaIvaId;
    private $motivoIsencao;
    private $quantidadeMinima;
    private $quantidadeCritica;
    private $quantidade;
    private $imagemProduto;
    private $codigoBarra;
    private $referencia;
    private $dataExpiracao;
    private $descricao;
    private $stocavel;
    private $vendaOnline;
    private $cartaGarantia;
    private $tempoGarantiaProduto;
    private $tipoGarantia;
    private $centroCustoId;
    private $imagemsUrl = [];

    /**
     * @param $designacao
     * @param $precoVenda
     * @param $precoCompra
     * @param $categoriaId
     * @param $unidadeMedidaId
     * @param $fabricanteId
     * @param $statusId
     * @param $taxaIvaId
     * @param $motivoIsencao
     * @param $quantidadeMinima
     * @param $quantidadeCritica
     * @param int $quantidade
     * @param $imagemProduto
     * @param $codigoBarra
     * @param $dataExpiracao
     * @param $descricao
     * @param $stocavel
     * @param $vendaOnline
     */


    public function __construct(
        $designacao,
        $precoVenda,
        $pvp,
        $precoCompra,
        $categoriaId,
        $tipoServicoId,
        $orderCategoria1,
        $orderCategoria2,
        $orderCategoria3,
        $unidadeMedidaId,
        $fabricanteId,
        $armazemId,
        $statusId,
        $taxaIvaId,
        $motivoIsencao,
        $quantidadeMinima = 0,
        $quantidadeCritica = 0,
        $quantidade = 0,
        $imagemProduto,
        $codigoBarra,
        $referencia,
        $dataExpiracao = null,
        $descricao = null,
        $stocavel = 'Y',
        $vendaOnline,
        $cartaGarantia,
        $tempoGarantiaProduto,
        $tipoGarantia,
        $centroCustoId
    )
    {
        $this->designacao = $designacao;
        $this->pvp = $pvp;
        $this->precoVenda = $precoVenda;
        $this->precoCompra = $precoCompra ?? 0;
        $this->categoriaId = $categoriaId;
        $this->tipoServicoId = $tipoServicoId;
        $this->orderCategoria1 = $orderCategoria1;
        $this->orderCategoria2 = $orderCategoria2;
        $this->orderCategoria3 = $orderCategoria3;
        $this->categoriaId = $categoriaId;
        $this->unidadeMedidaId = $unidadeMedidaId;
        $this->fabricanteId = $fabricanteId;
        $this->armazemId = $armazemId;
        $this->statusId = $statusId ?? 1;
        $this->taxaIvaId = $taxaIvaId;
        $this->motivoIsencao = $motivoIsencao;
        $this->quantidadeMinima = $quantidadeMinima ?? 0;
        $this->quantidadeCritica = $quantidadeCritica ?? 0;
        $this->quantidade = $quantidade ?? 0;
        $this->imagemProduto = $imagemProduto;
        $this->codigoBarra = $codigoBarra;
        $this->referencia = $referencia;
        $this->dataExpiracao = $dataExpiracao ?? null;
        $this->descricao = $descricao ?? null;
        $this->stocavel = $stocavel ?? "Sim";
        $this->vendaOnline = $vendaOnline ?? "Y";
        $this->cartaGarantia = $cartaGarantia ?? "Y";
        $this->tempoGarantiaProduto = $tempoGarantiaProduto;
        $this->tipoGarantia = $tipoGarantia;
        $this->centroCustoId = $centroCustoId;
    }

    public function addImagem($url)
    {
        array_push($this->imagemsUrl, $url);
    }


    /**
     * @return mixed
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }

    /**
     * @return mixed
     */
    public function getPrecoVenda()
    {
        return $this->precoVenda;
    }
    public function getPvp()
    {
        return $this->pvp;
    }

    /**
     * @return mixed
     */
    public function getPrecoCompra()
    {
        return $this->precoCompra;
    }

    /**
     * @return mixed
     */
    public function getCategoriaId()
    {
        return $this->categoriaId;
    }
    public function getTipoServidoId()
    {
        return $this->tipoServicoId;
    }
    public function getOrderCategoria1()
    {
        return $this->orderCategoria1;
    }
    public function getOrderCategoria2()
    {
        return $this->orderCategoria2;
    }
    public function getOrderCategoria3()
    {
        return $this->orderCategoria3;
    }


    /**
     * @return mixed
     */
    public function getUnidadeMedidaId()
    {
        return $this->unidadeMedidaId;
    }
    public function getReferencia(){
        return $this->referencia;
    }

    /**
     * @return mixed
     */
    public function getFabricanteId()
    {
        return $this->fabricanteId;
    }
    public function getArmazemId()
    {
        return $this->armazemId;
    }

    /**
     * @return mixed
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @return mixed
     */
    public function getTaxaIvaId()
    {
        return $this->taxaIvaId;
    }

    /**
     * @return mixed
     */
    public function getMotivoIsencao()
    {
        return $this->motivoIsencao;
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
    public function getQuantidade()
    {
        return $this->quantidade;
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
    public function getImagemProduto()
    {
        return $this->imagemProduto;
    }

    /**
     * @return mixed
     */
    public function getCodigoBarra()
    {
        return $this->codigoBarra;
    }


    /**
     * @return mixed
     */
    public function getDataExpiracao()
    {
        return $this->dataExpiracao;
    }

    public function getCartaGarantia()
    {

        return $this->cartaGarantia;
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
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @return mixed
     */
    public function getStocavel()
    {
        return $this->stocavel;
    }
    public function getCentroCustoId()
    {
        return $this->centroCustoId;
    }

    /**
     * @return mixed
     */
    public function getVendaOnline()
    {
        return $this->vendaOnline ? 'Y' : 'N';
    }

    /**
     * @return array
     */
    public function getImagemsUrl(): array
    {
        return $this->imagemsUrl;
    }
}
