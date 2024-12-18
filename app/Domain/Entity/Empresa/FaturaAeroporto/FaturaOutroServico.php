<?php

namespace App\Domain\Entity\Empresa\FaturaAeroporto;

class FaturaOutroServico
{

    private $tipoDocumento;
    private $formaPagamentoId;
    private $observacao;
    private $nomeProprietario;
    private $clienteId;
    private $nomeCliente;
    private $telefoneCliente;
    private $nifCliente;
    private $emailCliente;
    private $enderecoCliente;
    private $taxaIva;
    private $moeda;
    private $moedaPagamento;
    private $isencaoIva;
    private $retencao;
    private $valorRetencao;
    private $cambioDia;
    private $items = [];


    public function __construct($tipoDocumento, $formaPagamentoId, $observacao, $nomeProprietario, $clienteId, $nomeCliente, $telefoneCliente, $nifCliente, $emailCliente, $enderecoCliente, $taxaIva, $moeda, $moedaPagamento, $isencaoIVA, $retencao, $valorRetencao, $cambioDia)
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->observacao = $observacao;
        $this->isencaoIVA = $isencaoIVA;
        $this->retencao = $retencao;
        $this->valorRetencao = $valorRetencao;
        $this->nomeProprietario = $nomeProprietario;
        $this->clienteId = $clienteId;
        $this->nomeCliente = $nomeCliente;
        $this->telefoneCliente = $telefoneCliente;
        $this->nifCliente = $nifCliente;
        $this->emailCliente = $emailCliente;
        $this->enderecoCliente = $enderecoCliente;
        $this->taxaIva = $taxaIva;
        $this->cambioDia = $cambioDia;
        $this->moeda = $moeda;
        $this->moedaPagamento = $moedaPagamento;
    }

    public function getIsencaoIva(){
        return $this->isencaoIVA;
    }

    /**
     * @return mixed
     */

    public function addItem(FaturaItemOutroServico $items)
    {
        $this->items[] = $items;
    }
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }
    public function getFormaPagamentoId()
    {
        if ($this->getTipoDocumento() == 3) return null;
        if ($this->getTipoDocumento() == 2) return 2;
        return $this->formaPagamentoId;
    }
    public function getObservacao()
    {
        return $this->observacao;
    }

    public function getProprietario()
    {
        return $this->nomeProprietario;
    }
    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }
    public function getTelefoneCliente()
    {
        return $this->telefoneCliente;
    }
    public function getNifCliente()
    {
        return $this->nifCliente;
    }

    public function getEmailCliente()
    {
        return $this->emailCliente;
    }

    public function getEnderecoCliente()
    {
        return $this->enderecoCliente;
    }

    public function getTaxaIva()
    {
        return $this->taxaIva;
    }

    public function getMoeda()
    {
        return $this->moeda;
    }
    public function getMoedaPagamento()
    {
        return $this->moedaPagamento;
    }

    public function getRetencao()
    {
        return $this->retencao;
    }
    public function getTaxaRetencao()
    {
        return $this->valorRetencao;
    }

    public function getCambioDia()
    {
        return $this->cambioDia;
    }
    public function getValorRetencao()
    {
        return ($this->getValorIliquido() * $this->getTaxaRetencao()) / 100;
    }
    public function getContraValor()
    {
        return $this->getTotal() / $this->getCambioDia();
    }
    public function getValorLiquido(){
        return $this->getDesconto() + $this->getValorIliquido();
    }
    public function getDesconto(){
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getValorDesconto();
        }
        return $total;
    }
    public function getValorIliquido()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }
        $total = $total - $this->getDesconto();
        return $total;
    }
    public function getValorImposto()
    {
        return ($this->getValorIliquido() * $this->getTaxaIva()) / 100;
    }

    public function getTotal()
    {
        return $this->getValorIliquido() + $this->getValorImposto() - $this->getValorRetencao();
    }

    public function getItems(): array
    {
        return $this->items;
    }


}
