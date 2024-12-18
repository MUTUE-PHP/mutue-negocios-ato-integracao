<?php

namespace App\Domain\Entity\Empresa\FaturaAeroporto;

class FaturaCarga
{
    private $cartaDePorte;
    private $tipoDocumento;
    private $tipoOperacao;
    private $formaPagamentoId;
    private $isencaoIVA;
    private $isencaoCargaTransito;
    private $retencao;
    private $valorRetencao;
    private $clienteId;
    private $nomeCliente;
    private $nomeProprietario;
    private $telefoneCliente;
    private $nifCliente;
    private $emailCliente;
    private $enderecoCliente;
    private $peso;
    private $dataEntrada;
    private $dataSaida;
    private $nDias;
    private $taxaIva;
    private $cambioDia;
    private $moeda;
    private $moedaPagamento;
    private $observacao;
    private $items = [];

    public function __construct($cartaDePorte, $tipoDocumento, $tipoOperacao, $formaPagamentoId, $isencaoIVA,$isencaoCargaTransito, $retencao, $valorRetencao, $clienteId, $nomeCliente, $nomeProprietario, $telefoneCliente, $nifCliente, $emailCliente, $enderecoCliente, $peso, $dataEntrada, $dataSaida, $nDias, $taxaIva, $cambioDia, $moedaEstrageiraUsado, $moedaPagamento, $observacao)
    {
        $this->cartaDePorte = $cartaDePorte;
        $this->tipoDocumento = $tipoDocumento;
        $this->tipoOperacao = $tipoOperacao;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->isencaoIVA = $isencaoIVA;
        $this->isencaoCargaTransito = $isencaoCargaTransito;
        $this->retencao = $retencao;
        $this->valorRetencao = $valorRetencao;
        $this->clienteId = $clienteId;
        $this->nomeCliente = $nomeCliente;
        $this->nomeProprietario = $nomeProprietario;
        $this->telefoneCliente = $telefoneCliente;
        $this->nifCliente = $nifCliente;
        $this->emailCliente = $emailCliente;
        $this->enderecoCliente = $enderecoCliente;
        $this->peso = $peso;
        $this->dataEntrada = $dataEntrada;
        $this->dataSaida = $dataSaida;
        $this->nDias = $nDias;
        $this->taxaIva = $taxaIva;
        $this->cambioDia = $cambioDia;
        $this->moeda = $moedaEstrageiraUsado;
        $this->moedaPagamento = $moedaPagamento;
        $this->observacao = $observacao;
    }

    public function addItem(FaturaItemCarga $items)
    {
        $this->items[] = $items;
    }

    public function getNomeProprietario()
    {
        return $this->nomeProprietario;
    }

    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    public function getTelefone()
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


    /**
     * @return mixed
     */
    public function getTipoDocumentoId()
    {
        return $this->tipoDocumento;
    }

    /**
     * @return mixed
     */
    public function getTipoOperacao()
    {
        return $this->tipoOperacao;
    }

    /**
     * @return mixed
     */
    public function getFormaPagamentoId()
    {
        if ($this->getTipoDocumentoId() == 3) return null;
        if ($this->getTipoDocumentoId() == 2) return 2;
        return $this->formaPagamentoId;
    }

    /**
     * @return mixed
     */
    public function getIsencaoCargaTransito()
    {
        return $this->isencaoCargaTransito;
    }

    public function getIsencaoIVA()
    {
        return $this->isencaoIVA;
    }

    /**
     * @return mixed
     */
    public function getRetencao()
    {
        return $this->retencao;
    }

    /**
     * @return mixed
     */
    public function getTaxaRetencao()
    {
        return $this->valorRetencao;
    }


    public function getClienteId()
    {
        return $this->clienteId;
    }

    public function getCartaDePorte()
    {
        return $this->cartaDePorte;
    }

    /**
     * @return mixed
     */
    public function getPeso()
    {
        return $this->peso;
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
    public function getDataSaida()
    {
        return $this->dataSaida;
    }

    /**
     * @return mixed
     */
    public function getNDias()
    {
        return $this->nDias;
    }

    public function getTaxaIva()
    {
        if ($this->getIsencaoIVA()) return 0;
        return $this->taxaIva;
    }

    public function getMoeda()
    {
        return $this->moeda;
    }

    /**
     * @return mixed
     */
    public function getMoedaPagamento()
    {
        return $this->moedaPagamento;
    }


    public function getObservacao()
    {
        return $this->observacao;
    }

    public function getCambioDia()
    {
        return $this->cambioDia;
    }

    public function getContraValor()
    {
        return $this->getTotal() / $this->getCambioDia();
    }

    public function getItems(): array
    {
        return $this->items;
    }
    public function getValorLiquido(){
        return $this->getDesconto() + $this->getValorIliquido();
    }
    public function getDesconto(){
        $total = 0;
        foreach ($this->getItems() as $item){
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

    public function getValorRetencao()
    {
        return ($this->getValorIliquido() * $this->getTaxaRetencao()) / 100;
    }

}
