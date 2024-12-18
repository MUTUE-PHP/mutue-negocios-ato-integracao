<?php

namespace App\Domain\Entity\Empresa\EntradaProduto;

class EntradaProduto
{

   private $numeracaoFatura;
   private $fornecedorId;
   private $armazemId;
   private $formaPagamentoId;
   private $dataEntrada;
   private $dataFaturaFornecedor;
   private $descontoValor;
   private $descontoPercentagem;
   private $totalRetencao;
   private $descricao;
   private $statusPagamento;
   private $items = [];

    /**
     * @param $numeracaoFatura
     * @param $fornecedorId
     * @param $armazemId
     * @param $formaPagamentoId
     * @param $dataEntrada
     * @param $dataFaturaFornecedor
     * @param $descontoValor
     * @param $descontoPercentagem
     * @param $totalRetencao
     * @param $totalIva
     * @param $descricao
     * @param $statusPagamento
     */
    public function __construct($numeracaoFatura, $fornecedorId, $armazemId, $formaPagamentoId, $dataEntrada, $dataFaturaFornecedor, $descontoValor, $descontoPercentagem, $totalRetencao, $statusPagamento, $descricao)
    {
        $this->numeracaoFatura = $numeracaoFatura;
        $this->fornecedorId = $fornecedorId;
        $this->armazemId = $armazemId;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->dataEntrada = $dataEntrada;
        $this->dataFaturaFornecedor = $dataFaturaFornecedor;
        $this->descontoValor = $descontoValor;
        $this->descontoPercentagem = $descontoPercentagem;
        $this->totalRetencao = $totalRetencao;
        $this->statusPagamento = $statusPagamento;
        $this->descricao = $descricao;
        $this->items = [];
    }
    public function addItem(ItemEntradaProduto $item){
        array_push($this->items, $item);
    }

    /**
     * @return mixed
     */
    public function getNumeracaoFatura()
    {
        return $this->numeracaoFatura;
    }

    /**
     * @return mixed
     */
    public function getFornecedorId()
    {
        return $this->fornecedorId;
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
    public function getFormaPagamentoId()
    {
        return $this->formaPagamentoId;
    }

    /**
     * @return mixed
     */
    public function getDataEntrada()
    {
        return $this->dataEntrada;
    }

    /**
     * @return mixed
     */
    public function getDataFaturaFornecedor()
    {
        return $this->dataFaturaFornecedor;
    }
    public function getTotalCompras(){
        $total = 0;
        foreach ($this->getItems() as $item){
            $total +=  $item->getTotalCompra();
        }
        return $total - $this->getTotalDesconto();
    }
    public function getTotalSemImposto(){

        $total = 0;
        foreach ($this->getItems() as $item){
            $total +=  $item->getTotalSemImposto();
        }
        return $total;
    }
    public function getTotalVenda(){
        $total = 0;
        foreach ($this->getItems() as $item){
            $total +=  $item->getTotalVenda();
        }
        return $total;
    }
    public function getTotalLucro(){
        $total = 0;
        foreach ($this->getItems() as $item){
            $total +=  $item->getTotalLucro();
        }
        return $total;
    }
    public function getTotalTaxaIva(){
        $total = 0;
        foreach ($this->getItems() as $item){
            $total +=  $item->getTotalTaxaIva();
        }
        return $total;
    }
    public function getTotalDesconto(){
        $total = 0;
        foreach ($this->getItems() as $item){
            $total +=  $item->getTotalDesconto();
        }
        return $total;
    }

    public function getStatusPagamento(){
        return $this->statusPagamento;
    }
    public function getTotalRetencao()
    {
        return $this->totalRetencao;
    }

    /**
     * @return mixed
     */


    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }






}
