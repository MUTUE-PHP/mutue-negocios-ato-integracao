<?php

namespace App\Domain\Entity\VendasOnline;

class FaturaReciboVendaOnline
{

    private $items = [];
    private $codigo;
    private $bancoId;
    private $totalPagamento;
    private $taxaEntrega;
    private $totalDesconto;
    private $totalIva;
    private $formaPagamentoId;

    /**
     * @param $codigo
     * @param $bancoId
     * @param $totalPagamento
     * @param $taxaEntrega
     * @param $totalDesconto
     * @param $totalIva
     * @param $formaPagamentoId
     */
    public function __construct($codigo, $bancoId, $totalPagamento, $taxaEntrega, $totalDesconto, $totalIva, $formaPagamentoId)
    {
        $this->codigo = $codigo;
        $this->bancoId = $bancoId;
        $this->totalPagamento = $totalPagamento;
        $this->taxaEntrega = $taxaEntrega;
        $this->totalDesconto = $totalDesconto;
        $this->totalIva = $totalIva;
        $this->formaPagamentoId = $formaPagamentoId;
    }
    public function addItem($item){
        $this->items[] = $item;
    }
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @return mixed
     */
    public function getBancoId()
    {
        return $this->bancoId;
    }

    /**
     * @return mixed
     */
    public function getTotalPagamento()
    {
        return $this->totalPagamento;
    }

    /**
     * @return mixed
     */
    public function getTaxaEntrega()
    {
        return $this->taxaEntrega;
    }

    /**
     * @return mixed
     */
    public function getTotalDesconto()
    {
        return $this->totalDesconto;
    }

    /**
     * @return mixed
     */
    public function getTotalIva()
    {
        return $this->totalIva;
    }

    /**
     * @return mixed
     */
    public function getFormaPagamentoId()
    {
        return $this->formaPagamentoId;
    }



}
