<?php

namespace App\Domain\Entity\VendasOnline;

use Illuminate\Support\Facades\DB;

class PagamentoVendasOnline
{
    private $bancoId;
    private $dataPagamentoBanco;
    private $comprovativoBancario;
    private $formaPagamentoId;
    private $userId;
    private $statusPagamentoId;
    private $instanciaEntrega;
    private $estimativaEntrega;
    private $cobrarTaxaEntrega;
    private $numeroCartaoCliente;
    private $sequencia;
    private $items = [];

    private EnderecoEntregaVendasOnline $enderecoEntrega;

    /**
     * @param $bancoId
     * @param $dataPagamentoBanco
     * @param $comprovativoBancario
     * @param $formaPagamentoId
     * @param $userId
     * @param $statusPagamentoId
     * @param $instanciaEntrega
     * @param EnderecoEntregaVendasOnline $enderecoEntrega
     */
    public function __construct($bancoId, $dataPagamentoBanco, $comprovativoBancario, $formaPagamentoId, $userId, $statusPagamentoId, $instanciaEntrega,$estimativaEntrega,$cobrarTaxaEntrega, $numeroCartaoCliente, EnderecoEntregaVendasOnline $enderecoEntrega, $sequencia)
    {
        $this->bancoId = $bancoId;
        $this->dataPagamentoBanco = $dataPagamentoBanco;
        $this->comprovativoBancario = $comprovativoBancario;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->userId = $userId;
        $this->enderecoEntrega = $enderecoEntrega;
        $this->statusPagamentoId = $statusPagamentoId;
        $this->instanciaEntrega = $instanciaEntrega;
        $this->estimativaEntrega = $estimativaEntrega;
        $this->cobrarTaxaEntrega = $cobrarTaxaEntrega;
        $this->numeroCartaoCliente = $numeroCartaoCliente;
        $this->sequencia = $sequencia;
    }

    public function getTaxaCobrarTaxaEntrega(){
        return $this->cobrarTaxaEntrega;
    }

    public function addItem($item)
    {
        array_push($this->items, $item);
    }

    public function getBancoId()
    {
        return $this->bancoId;
    }

    public function getDataPagamentoBanco()
    {
        return $this->dataPagamentoBanco;
    }

    public function getComprovativoBancario()
    {
        return $this->comprovativoBancario;
    }

    public function getFormaPagamentoId()
    {
        return $this->formaPagamentoId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function getTaxaEntrega(){
        if(!$this->cobrarTaxaEntrega){
            return 0;
        }
        return $this->instanciaEntrega->getInstanciaEntrega()->getTaxaEntrega();
    }
    public function getEstimativaEntrega(){
        return $this->estimativaEntrega;
    }
    public function getNumeroCartaoCliente(){
        return $this->numeroCartaoCliente;
    }
    public function getEntrega()
    {
        return $this->instanciaEntrega;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTotalIva()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getTaxaValor();
        }
        return $total;
    }

    public function getTotalDesconto()
    {
        return 0;
    }
    public function getSubTotalPagamento(){

        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getSubtotal();
        }
        return $total;
    }

    public function getTotalPagamento()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getSubtotal();
        }
        return $total + $this->getTaxaEntrega();
    }
    public function getStatusPagamentoId()
    {
        return $this->statusPagamentoId;
    }

    public function getEnderecoEntrega(): EnderecoEntregaVendasOnline
    {
        return $this->enderecoEntrega;
    }

    public function getSequencia()
    {
        return $this->sequencia;
    }
}
