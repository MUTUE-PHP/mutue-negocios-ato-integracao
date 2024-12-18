<?php

namespace App\Domain\Entity\Empresa\Faturacao;

use NumberFormatter;

class Fatura
{
    public $PAGAMENTO_DUPLO = 6;
    public $PAGAMENTO_CREDITO = 2;
    private $clienteId;
    private $nomeCliente;
    private $nifCliente;
    private $emailCliente;
    private $numeroCartaoCliente;
    private $valorDescontarSaldo;
    private $aplicadoCartaoCliente;
    private $saldoCliente;
    private $telefoneCliente;
    private $enderecoCliente;
    private $contaCorrenteCliente;
    private $formaPagamentoId;
    private $armazemId;
    private $desconto;
    private $isRetencao;
    private $tipoDocumento;
    private $tipoFolha;
    private $totalEntregue;
    private $totalMulticaixa;
    private $totalCash;
    private $observacao;
    private $items = [];

    public function __construct(
        $clienteId,
        $nomeCliente,
        $nifCliente,
        $emailCliente,
        $numeroCartaoCliente,
        $aplicadoCartaoCliente,
        $saldoCliente,
        $telefoneCliente,
        $enderecoCliente,
        $contaCorrenteCliente,
        $formaPagamentoId,
        $armazemId,
        $desconto,
        $isRetencao,
        $tipoDocumento,
        $tipoFolha,
        $totalEntregue,
        $totalMulticaixa,
        $totalCash,
        $observacao)
    {
        $this->clienteId = $clienteId;
        $this->nomeCliente = $nomeCliente ?? 'Consumidor final';
        $this->nifCliente = $nifCliente ?? '999999999';
        $this->emailCliente = $emailCliente;
        $this->numeroCartaoCliente = $numeroCartaoCliente;
        $this->aplicadoCartaoCliente = $aplicadoCartaoCliente;
        $this->saldoCliente = $saldoCliente??0;
        $this->telefoneCliente = $telefoneCliente ?? '999999999';
        $this->enderecoCliente = $enderecoCliente;
        $this->contaCorrenteCliente = $contaCorrenteCliente;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->armazemId = $armazemId;
        $this->desconto = !is_numeric($desconto) || !$desconto ? 0: $desconto;
        $this->isRetencao = $isRetencao ?? false;
        $this->tipoDocumento = $tipoDocumento;
        $this->tipoFolha = $tipoFolha;
        $this->totalEntregue = !is_numeric($totalEntregue) || !$totalEntregue ? 0 :$totalEntregue;
        $this->totalMulticaixa = !is_numeric($totalMulticaixa) || !$totalMulticaixa? 0:$totalMulticaixa;
        $this->totalCash = !is_numeric($totalCash) || !$totalCash? 0:$totalCash;
        $this->observacao = $observacao;
    }
    public function addItem( FaturaItem $items){
        $this->items[] = $items;
    }
    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * @return mixed
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * @return mixed
     */
    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    /**
     * @return mixed
     */
    public function getNifCliente()
    {
        return $this->nifCliente;
    }

    /**
     * @return mixed
     */
    public function getEmailCliente()
    {
        return $this->emailCliente;
    }
    public function getNumeroCartaoCliente()
    {
        return $this->numeroCartaoCliente;
    }
    public function getValorDescontarSaldo(){

        if($this->getAplicadoCartaoCliente()){
            if(($this->getTotalEntregue() + $this->getTotalMulticaixa()) >= $this->getTotalPagar()){
                $this->valorDescontarSaldo = 0;
            }else{
                if($this->getTotalEntregue() > 0 || $this->getTotalMulticaixa() > 0){
                    $this->valorDescontarSaldo =  $this->getTotalPagar() - ($this->getTotalEntregue() + $this->getTotalMulticaixa());
                }else{
                    $this->valorDescontarSaldo = $this->getTotalPagar();
                }
            }
        }else{
            $this->valorDescontarSaldo = 0;
        }
        return $this->valorDescontarSaldo;
    }
    public function getAplicadoCartaoCliente(){
        return $this->aplicadoCartaoCliente;
    }
    public function getSaldoCliente(){
        return $this->saldoCliente;
    }

    /**
     * @return mixed
     */
    public function getTelefoneCliente()
    {
        return $this->telefoneCliente;
    }
    public function getEnderecoCliente()
    {
        return $this->enderecoCliente;
    }

    /**
     * @return mixed
     */
    public function getContaCorrenteCliente()
    {
        return $this->contaCorrenteCliente;
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
    public function getArmazemId()
    {
        return $this->armazemId;
    }

    /**
     * @return mixed
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * @return mixed
     */
    public function getIsRetencao()
    {
        return $this->isRetencao;
    }

    /**
     * @return mixed
     */
    public function getTipoDocumento()
    {
        if ($this->getFormaPagamentoId() == 2) {
            return 2;
        }else if($this->getFormaPagamentoId() == 1){
            return 1;
        }

        return $this->tipoDocumento;
    }
    public function getTipoFolha()
    {
        return $this->tipoFolha;
    }

    /**
     * @return mixed
     */
    public function getTotalEntregue()
    {

//        if($this->PAGAMENTO_DUPLO == $this->formaPagamentoId) return 0;
        if($this->PAGAMENTO_CREDITO == $this->formaPagamentoId) return 0;
        return $this->totalEntregue??0;
    }

    /**
     * @return mixed
     */
    public function getTotalMulticaixa()
    {
        if ($this->PAGAMENTO_CREDITO == $this->getFormaPagamentoId()) return 0;
        if ($this->getFormaPagamentoId() == 3) return $this->getTotalPagar();
        return $this->totalMulticaixa;
    }
    public function getObservacao(){
        return $this->observacao;
    }

    /**
     * @return mixed
     */
    public function getTotalCash()
    {
        if($this->PAGAMENTO_CREDITO == $this->formaPagamentoId) return 0;
        return $this->totalCash;
    }
    public function getTotalPrecoFactura()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->subTotalPrecoProduto();
        }
        return $total;
    }

    public function getTotalPagar()
    {

        return $this->getTotalPrecoFactura() + $this->getTotalIva() - ($this->getTotalRetencao() + $this->getTotalDesconto());
    }

    public function getTotalTroco()
    {
        if($this->PAGAMENTO_CREDITO == $this->formaPagamentoId) return 0;
        $troco = ($this->getTotalEntregue() + $this->getTotalMulticaixa()+ $this->getValorDescontarSaldo()) - $this->getTotalPagar();

        if($troco <= 0) return 0;
        if($this->getTotalPrecoFactura() <= 0) return 0;
        if($this->getAplicadoCartaoCliente()) return $troco;
        if($this->getTotalEntregue() <= 0 || $troco<=0) return 0;
        return $troco;
    }
    public function getTotalIncidencia(){
        $total = 0;
        foreach ($this->getItems() as $item){
            $total += $item->subTotalIncidencia();
        }
        if($this->desconto){
            return ($total  * $this->desconto)  / 100;
        }
        return $total;
    }

    public function getTotalDesconto()
    {
        $total = 0;
        foreach ($this->getItems() as $item){
            $total += $item->subTotalDesconto();
        }
        return $total;
    }

    public function getTotalRetencao()
    {
        $total = 0;
        if ($this->isRetencao) {
            $retencao = 6.5 / 100;
            $total = $this->getTotalIncidencia() * $retencao;
        }
        return $total;
    }

    public function getTotalIva()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->subTotalTaxaIva();
        }
        return $total;
    }

    public function getTotalExtenso()
    {
        $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);
        return $f->format($this->getTotalPagar());
    }


}
