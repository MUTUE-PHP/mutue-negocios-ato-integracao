<?php

namespace App\Domain\Entity\Empresa\Recibos;

class Recibo
{
    private $clienteId;
    private $nomeCliente;
    private $nifCliente;
    private $telefoneCliente;
    private $emailCliente;
    private $enderecoCliente;
    private $anulado = 1;
    private $totalEntregue;
    private $totalImposto;
    private $facturaId;

    private $totalFatura;
    private $totalDebitado;
    private $totalDebitar;
    private $formaPagamentoId;
    private $observacao;
    private $numSequenciaRecibo;
    private $numeracaoRecibo;
    private $numeroOperacaoBancaria;
    private $dataOperacao;
    private $comprovativoBancario;

    public function __construct($clienteId, $nomeCliente, $nifCliente, $telefoneCliente, $emailCliente, $enderecoCliente, int $anulado, $totalEntregue, $totalImposto, $facturaId, $totalFatura,$formaPagamentoId, $numeroOperacaoBancaria, $dataOperacao, $comprovativoBancario, $observacao, $numSequenciaRecibo, $numeracaoRecibo = null)
    {
        $this->clienteId = $clienteId;
        $this->nomeCliente = $nomeCliente;
        $this->nifCliente = $nifCliente;
        $this->telefoneCliente = $telefoneCliente;
        $this->emailCliente = $emailCliente;
        $this->enderecoCliente = $enderecoCliente;
        $this->anulado = $anulado;
        $this->totalEntregue = $totalEntregue;
        $this->totalImposto = $totalImposto;
        $this->facturaId = $facturaId;
        $this->totalFatura = $totalFatura;
        $this->formaPagamentoId = $formaPagamentoId;
        $this->numeroOperacaoBancaria = $numeroOperacaoBancaria;
        $this->dataOperacao = $dataOperacao;
        $this->comprovativoBancario = $comprovativoBancario;
        $this->observacao = $observacao;
        $this->numSequenciaRecibo = $numSequenciaRecibo;
        $this->numeracaoRecibo = $numeracaoRecibo;


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
    public function getTelefoneCliente()
    {
        return $this->telefoneCliente;
    }

    /**
     * @return mixed
     */
    public function getEmailCliente()
    {
        return $this->emailCliente;
    }

    /**
     * @return mixed
     */
    public function getEnderecoCliente()
    {
        return $this->enderecoCliente;
    }

    public function getAnulado(): int
    {
        return $this->anulado;
    }

    /**
     * @return mixed
     */
    public function getTotalEntregue()
    {
        return $this->totalEntregue;
    }
    public function getTotalImposto()
    {
        return $this->totalImposto;
    }

    /**
     * @return mixed
     */
    public function getFacturaId()
    {
        return $this->facturaId;
    }

    /**
     * @return mixed
     */
    public function getTotalFatura()
    {
        return $this->totalFatura;
    }

    /**
     * @return mixed
     */
    public function getTotalDebitado()
    {
        return $this->getTotalEntregue();
    }

    /**
     * @return mixed
     */
    public function getTotalDebitar()
    {
        return $this->getTotalFatura() - $this->getTotalEntregue();
    }
    public function getNumeracaoRecibo(){
        return $this->numeracaoRecibo;
    }

    /**
     * @return mixed
     */
    public function getFormaPagamentoId()
    {
        return $this->formaPagamentoId;
    }

    public function GetNumeroOperacaoBancaria()
    {
        return $this->numeroOperacaoBancaria;
    }
    public function GetDataOperacao()
    {
        return $this->dataOperacao;
    }
    public function GetcomprovativoBancario()
    {
        return $this->comprovativoBancario;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @return mixed
     */
    public function getNumSequenciaRecibo()
    {
        return $this->numSequenciaRecibo;
    }




}
